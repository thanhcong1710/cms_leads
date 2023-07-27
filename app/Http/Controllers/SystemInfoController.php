<?php

namespace App\Http\Controllers;

use App\Providers\UtilityServiceProvider as u;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SystemInfoController extends Controller
{
    
    public function getAllProvices()
    {
        $data = u::getMultiObject([], 'cms_provinces');
        return response()->json($data);
    }
    public function getDistrictsByProvice($province_id){
        $data = u::getMultiObject(['province_id'=>$province_id], 'cms_districts');
        return response()->json($data);
    }
    public function getAllJobs()
    {
        $data = u::query("SELECT * FROM cms_jobs WHERE status=1");
        return response()->json($data);
    }
    public function getAllSources(){
        $data = u::query("SELECT * FROM cms_sources WHERE status=1");
        return response()->json($data);
    }
    public function getAllSourceDetail(Request $request){
        $cond= $request->user()->branch_id && !$request->user()->hasRole('admin') ? " AND (branch_id =".$request->user()->branch_id." OR branch_id IS NULL OR branch_id=0)" :"";
        $data = u::query("SELECT * FROM cms_source_detail WHERE status=1 $cond");
        return response()->json($data);
    }
    public function getAllMethods(){
        $data = u::query("SELECT * FROM cms_contact_methods WHERE status=1");
        return response()->json($data);
    }
    public function getAllTemplateNote(){
        $data = u::query("SELECT * FROM cms_template_note WHERE status=1");
        return response()->json($data);
    }
    public function getAllBranches(Request $request){
        $permission = $request->permission;
        $cond = "";
        if($permission ==1 && $request->user()->branch_id!=0){
            $cond = " AND id=".$request->user()->branch_id;
        }
        $data = u::query("SELECT * FROM cms_branches WHERE status=1 $cond");
        return response()->json($data);
    }
    public function getAllReportWeekReport(Request $request){
        $type= $request->type ? $request->type :0 ;
        if($type == 'all'){
            $cond = "1";
        }elseif($type==1){
            $cond = "start_date >='".date('Y-m-d',strtotime('previous monday'))."' ORDER BY start_date";
        }else{
            $cond = "start_date <='".date('Y-m-d')."' ORDER BY start_date DESC";
        }
        
        $data = u::query("SELECT *, CONCAT('Tuần từ ngày ',start_date,' đến ', end_date) AS label FROM cms_report_week WHERE $cond");
        return response()->json($data);
    }
    public function getSchools($province_id, $district_id, $school_level)
    {
        $data = null;
        if ($province_id && $district_id && $school_level) {
            $data = u::query("SELECT `name`, `level`, address, id FROM cms_schools WHERE `level` = '$school_level' AND district_id = $district_id AND province_id = $province_id");
            if (count($data)) {
                foreach($data as $i => $o) {
                    if (mb_strtolower(substr($o->name, 0, strlen($o->level))) != mb_strtolower($o->level)) {
                        $name = $o->name;
                        $o->name = "$o->level $name";
                    }
                }
            }
        }
        return response()->json($data);
    }
    public function getListSourceDetail(Request $request)
    {
        $status = isset($request->status) ? $request->status : '';
        $keyword = isset($request->keyword) ? $request->keyword : '';
        
        $pagination = (object)$request->pagination;
        $page = isset($pagination->cpage) ? (int) $pagination->cpage : 1;
        $limit = isset($pagination->limit) ? (int) $pagination->limit : 20;
        $offset = $page == 1 ? 0 : $limit * ($page-1);
        $limitation =  $limit > 0 ? " LIMIT $offset, $limit": "";
        $cond = " 1 ";
        if($status!==''){
            $cond .= " AND s.status=$status";
        }
        if($keyword!==''){
            $cond .= " AND s.name LIKE '%$keyword%'";
        }
        if(!$request->user()->hasRole('admin')){
            $edit = "IF(".$request->user()->branch_id." = s.branch_id,1,0) AS can_edit";
            $cond .= " AND ( s.branch_id =".$request->user()->branch_id." OR s.branch_id IS NULL)";
        }else{
            $edit = "1 AS can_edit";
        }
        $total = u::first("SELECT count(id) AS total FROM cms_source_detail AS s WHERE $cond ");
        $list = u::query("SELECT s.*,$edit ,(SELECT name FROM cms_branches WHERE id =s.branch_id) AS branch_name
            FROM cms_source_detail AS s WHERE $cond ORDER BY s.id DESC $limitation");
        $data = u::makingPagination($list, $total->total, $page, $limit);
        return response()->json($data);
    }
    public function addSourceDetail(Request $request)
    {
        $branch_id = !$request->user()->hasRole('admin') ? $request->user()->branch_id : NULL;
        $id = u::insertSimpleRow(array(
            'name'=>$request->name,
            'created_at' => date('Y-m-d H:i:s'),
            'creator_id' => Auth::user()->id,
            'status'=>$request->status,
            'branch_id'=>$branch_id
        ), 'cms_source_detail');
        return response()->json($id);
    }
    public function infoSourceDetail(Request $request,$source_detail_id)
    {
        $data = u::first("SELECT * FROM cms_source_detail WHERE id=$source_detail_id");
        return response()->json($data);
    }
    public function updateSourceDetail(Request $request,$source_detail_id)
    {
        $id = u::updateSimpleRow(array(
            'name'=>$request->name,
            'updated_at' => date('Y-m-d H:i:s'),
            'updator_id' => Auth::user()->id,
            'status'=>$request->status,
        ), array('id'=>$source_detail_id),'cms_source_detail');
        return response()->json($id);
    }
    public function getListTarget(Request $request)
    {
        $week_id = isset($request->week_id) ? $request->week_id : '';
        $keyword = isset($request->keyword) ? $request->keyword : '';
        
        $pagination = (object)$request->pagination;
        $page = isset($pagination->cpage) ? (int) $pagination->cpage : 1;
        $limit = isset($pagination->limit) ? (int) $pagination->limit : 20;
        $offset = $page == 1 ? 0 : $limit * ($page-1);
        $limitation =  $limit > 0 ? " LIMIT $offset, $limit": "";
        $cond = " 1 ";
        if($keyword!==''){
            $cond .= " AND (u.name LIKE '%$keyword%' OR u.hrm_id LIKE '%$keyword%')";
        }
        if($week_id!==''){
            $cond .= " AND r.report_week_id= $week_id";
        }
        if(!$request->user()->hasRole('admin')){
            $cond .= " AND r.user_id IN (".$request->user_info->users_manager.")";
        }
        
        $total = u::first("SELECT count(r.id) AS total FROM cms_report_target AS r LEFT JOIN users AS u ON u.id=r.user_id WHERE $cond ");
        $list = u::query("SELECT r.*,CONCAT(u.name,' - ',u.hrm_id) AS user_label,CONCAT('Tuần từ ngày ',w.start_date,' đến ',w.end_date) AS week_label,
            IF(w.end_date < CURRENT_DATE,0,1) AS can_edit 
            FROM cms_report_target AS r 
                LEFT JOIN users AS u ON u.id=r.user_id 
                LEFT JOIN cms_report_week AS w ON w.id=r.report_week_id
            WHERE $cond ORDER BY r.id DESC $limitation");
        $data = u::makingPagination($list, $total->total, $page, $limit);
        return response()->json($data);
    }
    public function addTarget(Request $request)
    {
        $target_info = u::first("SELECT id FROM cms_report_target WHERe user_id=$request->user_id  AND report_week_id=$request->week_id");
        if($target_info){
            $result = array(
              'status'=>0,
              'message'=>'Đã tồn tại bản của ghi'
            );  
        }else{
            $id = u::insertSimpleRow(array(
                'report_week_id'=>$request->week_id,
                'user_id'=>$request->user_id,
                'call'=>$request->call,
                'talk_time'=>$request->talk_time,
                'trial_accept'=>$request->trial_accept,
                'trial_actual'=>$request->trial_actual,
                'new_enroll'=>$request->new_enroll,
                'created_at' => date('Y-m-d H:i:s'),
                'creator_id' => Auth::user()->id,
            ), 'cms_report_target');
            $result = array(
                'status'=>1,
                'message'=>'ok'
            ); 
        }
        return response()->json($result);
    }
    public function infoTarget(Request $request,$target_id)
    {
        $data = u::first("SELECT r.*,(SELECT CONCAT('Tuần từ ngày ',start_date,' đến ',end_date ) FROM cms_report_week WHERE id=r.report_week_id) AS report_week_label, 
                (SELECT CONCAT(name, ' - ',hrm_id) FROM users WHERE id=r.user_id) AS user_label
            FROM cms_report_target AS r WHERE id=$target_id");
        return response()->json($data);
    }
    public function updateTarget(Request $request,$target_id)
    {
        $id = u::updateSimpleRow(array(
            'call'=>$request->call,
            'talk_time'=>$request->talk_time,
            'trial_accept'=>$request->trial_accept,
            'trial_actual'=>$request->trial_actual,
            'new_enroll'=>$request->new_enroll,
            'updated_at' => date('Y-m-d H:i:s'),
            'updator_id' => Auth::user()->id
        ), array('id'=>$target_id),'cms_report_target');
        return response()->json($id);
    }
}
