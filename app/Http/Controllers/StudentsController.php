<?php

namespace App\Http\Controllers;

use App\Models\LogParents;
use App\Providers\UtilityServiceProvider as u;
use App\Providers\CurlServiceProvider as curl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentsController extends Controller
{
    public function add(Request $request)
    {
        if($request->id>0){
            $pre_student_info = u::first("SELECT * FROM cms_students WHERE id= $request->id");
            $data_update = array(
                'parent_id'=>$request->parent_id,
                'name'=>$request->name,
                'gender' => $request->gender,
                'birthday' => $request->birthday,
                'school_level' => $request->school_level,
                'school' => $request->school,
                'note' => $request->note,
                'updated_at' => date('Y-m-d H:i:s'),
                'updator_id' => Auth::user()->id,
            );
            u::updateSimpleRow($data_update,array('id'=>$request->id), 'cms_students');
            LogParents::logUpdateStudentInfo($pre_student_info,$data_update,Auth::user()->id);
        }else{
            $data = u::insertSimpleRow(array(
                'parent_id'=>$request->parent_id,
                'name'=>$request->name,
                'gender' => $request->gender,
                'birthday' => $request->birthday,
                'school_level' => $request->school_level,
                'school' => $request->school,
                'note' => $request->note,
                'created_at' => date('Y-m-d H:i:s'),
                'creator_id' => Auth::user()->id,
            ), 'cms_students');
            $content = "Thêm mới học sinh: $request->name (ID: $data)";
            LogParents::logAdd($request->parent_id,$content,Auth::user()->id);
        }
        return response()->json("ok");
    }
    public function getAllDataByParent($parent_id){
        $data = u::query("SELECT s.*, (SELECT name FROM users WHERE id=s.creator_id) AS creator_name,
                (SELECT name FROM cms_branches WHERE id=s.checkin_branch_id) AS checkin_branch_name
            FROM cms_students AS s WHERE s.parent_id=$parent_id ORDER BY s.id DESC");
        return response()->json($data);
    }
    public function checkin(Request $request){
        $crm_id= self::createCheckinCRM($request->student_id,$request->checkin_at,$request->branch_id);
        if($crm_id){
            $data_update = array(
                'checkin_at'=>$request->checkin_at,
                'checkin_branch_id'=>$request->branch_id,
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s'),
                'updator_id' => Auth::user()->id,
                'crm_id'=>$crm_id
            );
            u::updateSimpleRow($data_update,array('id'=>$request->student_id), 'cms_students');
        }
        return response()->json("ok");
    }
    public static function createCheckinCRM($student_id,$checkin_at,$checkin_branch_id){
        $student_info = u::first("SELECT s.*,p.name AS gud_name,p.email AS gud_email,p.address,p.province_id,p.district_id,
                p.mobile_1 AS gud_mobile_1,
                p.mobile_2 AS gud_mobile_2,
                p.gender AS gud_gender,
                p.birthday AS gud_birthday,
                p.source_id AS source, 
                p.job_id AS  gud_job,
                (SELECT hrm_id FROM users WHERE id=p.owner_id) AS owner_hrm,
                (SELECT crm_id FROM cms_students WHERE parent_id=p.id AND id !=s.id AND crm_id IS NOT NULL LIMIT 1) AS sibling_id 
            FROM cms_students AS s LEFT JOIN cms_parents AS p ON p.id=s.parent_id WHERE s.id=$student_id");
        $method = "POST";
        $data = array(
            'student_name' => $student_info->name,
            'student_gender' => $student_info->gender,
            'student_note' => $student_info->note,
            'student_date_of_birth'=>$student_info->birthday,
            'gud_name'=>$student_info->gud_name,
            'gud_email'=>$student_info->gud_email,
            'gud_mobile_1'=>$student_info->gud_mobile_1,
            'gud_mobile_2'=>$student_info->gud_mobile_2,
            'gud_gender'=>$student_info->gud_gender,
            'gud_birthday'=>$student_info->gud_birthday,
            'gud_job'=>$student_info->gud_job,
            'source'=>$student_info->source,
            'address'=>$student_info->address,
            'province_id'=>$student_info->province_id,
            'district_id'=>$student_info->district_id,
            'branch_id'=>$checkin_branch_id,
            'checkin_at'=>$checkin_at,
            'ec_hrm'=>$student_info->owner_hrm,
            'creator_hrm' => Auth::user()->hrm_id,
            'sibling_id' => $student_info->sibling_id ? $student_info->sibling_id : 0,
        );
        if(env('APP_ENV', 'staging')=='production'){
            $url = sprintf('%s/api/leads-create-checkin', 'https://crm.cmsedu.vn/');
        }else{
            $url = sprintf('%s/api/leads-create-checkin', 'https://staging.cmsedu.vn/');
        }
        $res = curl::curl($url, $method,[],$data);
        u::logRequest($url,$method,[],$data,$res,'log_request_outbound');
        $res = json_decode($res);
        if(isset($res->data->student_id)){
            return $res->data->student_id;
        }else{
            return NULL;
        }
    }
}
