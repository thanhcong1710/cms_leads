<?php

namespace App\Http\Controllers;

use App\Models\LogParents;
use App\Models\Sms;
use App\Providers\UtilityServiceProvider as u;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $status = isset($request->status) ? $request->status : '';
        $keyword = isset($request->keyword) ? $request->keyword : '';
        $owner_id = isset($request->owner_id) ? $request->owner_id : '';
        $end_date = isset($request->end_date) ? $request->end_date : '';
        $start_date = isset($request->start_date) ? $request->start_date : '';

        $pagination = (object)$request->pagination;
        $page = isset($pagination->cpage) ? (int) $pagination->cpage : 1;
        $limit = isset($pagination->limit) ? (int) $pagination->limit : 20;
        $offset = $page == 1 ? 0 : $limit * ($page-1);
        $limitation =  $limit > 0 ? " LIMIT $offset, $limit": "";
        $cond = " 1 ";
        if(!$request->user()->hasRole('admin')){
            $cond .= " AND p.owner_id IN (".$request->user_info->users_manager.")";
        }
        if ($status !== '') {
            $cond .= " AND p.status=$status";
        }
        if ($keyword !== '') {
            $cond .= " AND (p.name LIKE '%$keyword%' OR p.mobile_1 LIKE '%$keyword%' OR p.mobile_2 LIKE '%$keyword%') ";
        }
        if ($owner_id !== '') {
            $cond .= " AND p.owner_id=$owner_id";
        }
        if ($end_date !== '') {
            $cond .= " AND p.next_care_date < '$end_date 23:59:59' AND p.next_care_date IS NOT NULL";
        }
        if ($start_date !== '') {
            $cond .= " AND p.next_care_date > '$start_date 00:00:00'";
        }
        $total = u::first("SELECT count(id) AS total FROM cms_parents AS p WHERE $cond ");
        $list = u::query("SELECT p.*, (SELECT name FROM cms_sources WHERE id=p.source_id) AS source_name,
                (SELECT care_date FROM cms_customer_care WHERE parent_id=p.id ORDER BY care_date DESC LIMIT 1) AS last_care,
                (SELECT name FROM users WHERE id=p.owner_id) AS owner_name 
            FROM cms_parents AS p WHERE $cond ORDER BY p.id DESC $limitation");
        $data = u::makingPagination($list, $total->total, $page, $limit);
        return response()->json($data);
    }
    public function add(Request $request)
    {
        $id = u::insertSimpleRow(array(
            'name'=>$request->name,
            'email'=>$request->email,
            'mobile_1' => $request->mobile_1,
            'mobile_2' => $request->mobile_2,
            'address' => $request->address,
            'province_id' => $request->province_id,
            'district_id' => $request->district_id,
            'gender' => $request->gender,
            'birthday' => $request->birthday,
            'job_id' => $request->job_id,
            'source_id' => $request->source_id,
            'note' => $request->note,
            'created_at' => date('Y-m-d H:i:s'),
            'creator_id' => Auth::user()->id,
            'owner_id'=>$request->owner_id,
            'status'=>$request->status,
            'c2c_mobile'=>$request->c2c_mobile,
        ), 'cms_parents');
        LogParents::logAdd($id,'Khởi tạo khách hàng thủ công',Auth::user()->id);
        return response()->json($id);
    }
    public function detail($parent_id)
    {
        $data = u::getObject(array('id' => $parent_id), 'cms_parents');
        return response()->json($data);
    }
    public function update(Request $request, $parent_id)
    {
        $pre_parent_info = u::first("SELECT * FROM cms_parents WHERE id = $parent_id");
        $data_update = array(
            'name'=>$request->name,
            'email'=>$request->email,
            'mobile_1' => $request->mobile_1,
            'mobile_2' => $request->mobile_2,
            'address' => $request->address,
            'province_id' => $request->province_id,
            'district_id' => $request->district_id,
            'gender' => $request->gender,
            'birthday' => $request->birthday,
            'job_id' => $request->job_id,
            'source_id' => $request->source_id,
            'note' => $request->note,
            'updated_at' => date('Y-m-d H:i:s'),
            'updator_id' => Auth::user()->id,
            'owner_id'=>$request->owner_id,
            'status'=>$request->status,
            'c2c_mobile'=>$request->c2c_mobile,
        );
        $data = u::updateSimpleRow($data_update, array('id' => $parent_id), 'cms_parents');
        LogParents::logUpdateInfo($pre_parent_info,$data_update,Auth::user()->id);
        return response()->json($data);
    }
    public function assign(Request $request)
    {
        $pre_parent_info = u::first("SELECT owner_id FROM cms_parents WHERE id=$request->parent_id");
        $data = u::updateSimpleRow(array(
            'updated_at' => date('Y-m-d H:i:s'),
            'updator_id' => Auth::user()->id,
            'owner_id'=>$request->owner_id,
        ), array('id' => $request->parent_id), 'cms_parents');
        LogParents::logAssign($request->parent_id,$pre_parent_info->owner_id,$request->owner_id,Auth::user()->id);
        return response()->json($data);
    }
    public function assignList(Request $request)
    {
        $cond = implode(",",$request->parents);
        $arr_owner = $request->owners;
        $list_parent_info = u::query("SELECT p.id AS parent_id,p.owner_id,(SELECT CONCAT(name,' (',hrm_id,')') FROM users WHERE id= p.owner_id) AS pre_owner FROM cms_parents AS p WHERE p.id IN ($cond)");
        foreach($list_parent_info AS $k=>$row){
            $owner_id =  $arr_owner[$k%count($arr_owner)];
            u::query("UPDATE cms_parents SET owner_id= $owner_id WHERE id =$row->parent_id");
            LogParents::logAssign($row->parent_id,$row->owner_id,$owner_id,Auth::user()->id);
        }
        return response()->json("ok");
    }
    public function changeStaus(Request $request)
    {
        $pre_parent_info = u::first("SELECT status FROM cms_parents WHERE id=$request->parent_id");
        $data = u::updateSimpleRow(array(
            'updated_at' => date('Y-m-d H:i:s'),
            'updator_id' => Auth::user()->id,
            'status'=>$request->status,
        ), array('id' => $request->parent_id), 'cms_parents');
        LogParents::logStatus($request->parent_id,$pre_parent_info->status,$request->status,Auth::user()->id);
        return response()->json($data);
    }
    public function show($parent_id)
    {
        $data = u::first("SELECT p.*,(SELECT name FROM users WHERE id=p.creator_id) AS creator_name,
                (SELECT name FROM cms_districts WHERE id=p.district_id) AS district_name,
                (SELECT name FROM cms_provinces WHERE id=p.province_id) AS province_name,
                (SELECT name FROM cms_sources WHERE id=p.source_id) AS source_name,
                (SELECT title FROM cms_jobs WHERE id=p.job_id) AS job_name,
                (SELECT count(id) FROM cms_customer_care WHERE parent_id=p.id) AS num_care,
                (SELECT care_date FROM cms_customer_care WHERE parent_id=p.id ORDER BY care_date DESC LIMIT 1) AS last_care
            FROM cms_parents AS p WHERE id=$parent_id");
        $data->next_care_date =  $data->next_care_date ? date("Y-m-d\TH:i", strtotime($data->next_care_date)): $data->next_care_date;
        return response()->json($data);
    }
    public function delete($parent_id)
    {
        $data = u::query("DELETE FROM cms_parents WHERE id=$parent_id");
        return response()->json($data);
    }
    public function getLogs($parent_id){
        $data = u::query("SELECT l.*,(SELECT name FROM users WHERE id=l.creator_id) AS creator_name
            FROM cms_parent_logs AS l WHERE l.parent_id=$parent_id ORDER BY l.id DESC");
        return response()->json($data);
    }
    public function validatePhone(Request $request){
        $parent_id = isset($request->parent_id) ? $request->parent_id : '';
        $phone = isset($request->phone) ? $request->phone : '';
        $result =(object)array(
            'status'=>1,
            'message'=>''
        );
        if($parent_id){
            $duplicate_info = u::first("SELECT p.is_lock,u.name,u.hrm_id, u.branch_name FROM cms_parents AS p LEFT JOIN users AS u ON u.id=p.owner_id  WHERE (p.mobile_1='$phone' OR p.mobile_2='$phone') AND p.id!='$parent_id'");
            if($duplicate_info){
                $result->status = 0;
                $result->message = "Khách hàng có SĐT: $phone đang thuộc quyền quản lý của nhân viên $duplicate_info->name - $duplicate_info->hrm_id $duplicate_info->branch_name";
            }
        }else{
            $duplicate_info = u::first("SELECT p.is_lock,u.name,u.hrm_id, u.branch_name FROM cms_parents AS p LEFT JOIN users AS u ON u.id=p.owner_id  WHERE (p.mobile_1='$phone' OR p.mobile_2='$phone') ");
            if($duplicate_info){
                if($duplicate_info->is_lock==0){
                    $result->status = 2;
                    $result->message = "Khách hàng có SĐT: $phone đã tồn tại trên hệ thống, bạn có muốn chăm sóc?";
                }else{
                    $result->status = 0;
                    $result->message = "Khách hàng có SĐT: $phone đang thuộc quyền quản lý của nhân viên $duplicate_info->name - $duplicate_info->hrm_id $duplicate_info->branch_name";
                }
            }
        }
        return response()->json($result);
    }
    public function overwrite(Request $request){
        $phone = isset($request->phone) ? $request->phone : '';
        $parent_info = u::first("SELECT * FROM cms_parents WHERE mobile_1='$phone'");
        if($parent_info){
            u::updateSimpleRow(array(
                'updated_at' => date('Y-m-d H:i:s'),
                'updator_id' => Auth::user()->id,
                'owner_id'=>Auth::user()->id,
            ), array('id' => $parent_info->id), 'cms_parents');
            u::insertSimpleRow(array(
                'parent_id'=>$parent_info->id,
                'last_owner_id'=>$parent_info->owner_id,
                'owner_id'=>Auth::user()->id,
                'created_at'=>date('Y-m-d H:i:s'),
                'creator_id'=>Auth::user()->id,
            ), 'cms_parent_overwrite');
            LogParents::logAssign($parent_info->id,$parent_info->owner_id,Auth::user()->id,Auth::user()->id,true);
        }
        return "ok";
    }
    public function makeToCall(Request $request,$parent_id){
        $parent_info = u::first("SELECT * FROM cms_parents WHERE id='$parent_id'");
        $phone = $request->phone ? $request->phone :$parent_info->mobile_1;
        if($parent_info){
            $voip = new VoipController();
            $voip->makeToCall($phone,$request->user()->sip_id);
        }
        return "ok";
    }
    public function getInfoByPhone($phone){
        $parent_info = u::first("SELECT name,id,mobile_1 FROM cms_parents WHERE mobile_1='$phone'");
        return response()->json($parent_info);
    }
    public function sendSms(Request $request){
        $parent_info = u::first("SELECT id,mobile_1,mobile_2 FROM cms_parents WHERE id='$request->parent_id'");
        $phone = $request->phone ? $request->phone :$parent_info->mobile_1;
        if($parent_info){
            u::insertSimpleRow( array(
                'parent_id'=>$parent_info->id,
                'note'=>$request->content,
                'created_at'=>date('Y-m-d H:i:s'),
                'creator_id'=>$request->user()->id,
                'method_id'=>4,
                'care_date'=>date('Y-m-d H:i:s'),
                'phone'=>$phone 
            ),'cms_customer_care');
            $sms = new Sms();
            $sms->sendSms($phone,$request->content,$request->user()->id);
        }
        return "ok";
    }
    public function updateNextCareDate(Request $request){
        $data=u::updateSimpleRow(array(
            'updated_at' => date('Y-m-d H:i:s'),
            'updator_id' => $request->user()->id,
            'next_care_date'=>date('Y-m-d H:i:s',strtotime($request->next_care_date)),
        ), array('id' => $request->parent_id), 'cms_parents');
        LogParents::logAdd($request->parent_id,'Cập nhật lịch chăm sóc tiếp theo: '.date('Y-m-d H:i:s',strtotime($request->next_care_date)),$request->user()->id);
        return "ok";
    }
    public function validateC2CPhone(Request $request){
        $phone = isset($request->phone) ? $request->phone : '';
        
        $parent_info=u::first("SELECT name,mobile_1,mobile_2 FROM cms_parents WHERE mobile_1 = '$phone' OR mobile_2 ='$phone'");
        if($parent_info){
            $result =(object)array(
                'status'=>1,
                'message'=> "Khách hàng: ".$parent_info->name." - ".$parent_info->mobile_1.($parent_info->mobile_2?" - ".$parent_info->mobile_2:'')
            );
        }else{
            $result =(object)array(
                'status'=>0,
                'message'=>'Không tồn tại số điện thoại của khách hàng giới thiệu trên hệ thống'
            );
        }
        return response()->json($result);
    }
}
