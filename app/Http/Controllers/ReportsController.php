<?php

namespace App\Http\Controllers;

use App\Providers\UtilityServiceProvider as u;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function report01(Request $request)
    {
        $keyword = isset($request->keyword) ? $request->keyword : '';
        
        $pagination = (object)$request->pagination;
        $page = isset($pagination->cpage) ? (int) $pagination->cpage : 1;
        $limit = isset($pagination->limit) ? (int) $pagination->limit : 20;
        $offset = $page == 1 ? 0 : $limit * ($page-1);
        $limitation =  $limit > 0 ? " LIMIT $offset, $limit": "";
        $cond = " 1 ";
        if($keyword!==''){
            $cond .= " AND (p.name LIKE '%$keyword%' OR p.mobile_1 LIKE '%$keyword%' OR p.mobile_2 LIKE '%$keyword%')";
        }
        if($request->start_date){
            $cond .= " AND p.created_at >= '$request->start_date 00:00:00'";
        }
        if($request->end_date){
            $cond .= " AND p.created_at <= '$request->end_date 23:59:59'";
        }
        if(!$request->user()->hasRole('admin') && !$request->user()->hasRole('Supervisor')){
            $cond .= " AND p.owner_id IN (".$request->user_info->users_manager.")";
        }
        $total = u::first("SELECT count(p.id) AS total FROM cms_parents AS p 
                    LEFT JOIN users AS u ON p.owner_id = u.id
                    LEFT JOIN cms_branches AS b ON b.id = u.branch_id WHERE $cond ");
        $list = u::query("SELECT p.id, p.name AS parent_name,p.status,
                (SELECT name FROM cms_students WHERE parent_id=p.id LIMIT 1) AS student_name,
                (SELECT DATE_FORMAT(birthday,'%Y') FROM cms_students WHERE parent_id=p.id  LIMIT 1) AS student_year,
                (SELECT name FROM cms_sources WHERE id=p.source_id) AS source_name,
                CONCAT(u.name,'-',u.hrm_id) AS owner_name,
                b.name AS branch_name,
                DATE_FORMAT(p.created_at,'%Y-%m-%d') AS created_date,
                (SELECT DATE_FORMAT(care_date,'%Y-%m-%d') FROM cms_customer_care WHERE parent_id =p.id ORDER BY id DESC LIMIT 1) AS last_care_date,
                (SELECT t.name FROM cms_customer_care AS c LEFT JOIN cms_contact_methods AS t ON t.id=c.method_id WHERE c.parent_id =p.id ORDER BY c.id DESC LIMIT 1) AS last_method,
                (SELECT count(c.id) FROM cms_customer_care AS c WHERE c.parent_id =p.id ) AS total_care
            FROM cms_parents AS p 
                LEFT JOIN users AS u ON p.owner_id = u.id
                LEFT JOIN cms_branches AS b ON b.id = u.branch_id
            WHERE $cond ORDER BY p.id DESC $limitation");
            
        $data = u::makingPagination($list, $total->total, $page, $limit);
        return response()->json($data);
    }
    public function report02(Request $request)
    {
        $keyword = isset($request->keyword) ? $request->keyword : '';
        $report_week_id = isset($request->report_week_id) ? $request->report_week_id : 0;
        if(!$report_week_id){
            $report_week_info = u::first("SELECT * FROM cms_report_week WHERE start_date <= CURRENT_DATE AND  end_date>= CURRENT_DATE");
            $report_week_id = $report_week_info->id;
        }
        
        $pagination = (object)$request->pagination;
        $page = isset($pagination->cpage) ? (int) $pagination->cpage : 1;
        $limit = isset($pagination->limit) ? (int) $pagination->limit : 20;
        $offset = $page == 1 ? 0 : $limit * ($page-1);
        $limitation =  $limit > 0 ? " LIMIT $offset, $limit": "";
        $cond = " r.report_week_id =  $report_week_id";
        if($keyword!==''){
            $cond .= " AND (u.name LIKE '%$keyword%' OR u.hrm_id LIKE '%$keyword%')";
        }
        if(!$request->user()->hasRole('admin') && !$request->user()->hasRole('Supervisor')){
            $cond .= " AND u.id IN (".$request->user_info->users_manager.")";
        }
        $total = u::first("SELECT count(u.id) AS total FROM users AS u 
            LEFT JOIN cms_report_week_sale_hub AS r ON r.user_id=u.id
            WHERE u.status=1 AND $cond AND (SELECT count(role_id) FROM model_has_roles WHERE model_id=u.id AND role_id=4)>0");
        $list = u::query("SELECT CONCAT(u.name,' - ',u.hrm_id) AS user_label,
                t.call AS target_call, t.talk_time AS target_talk_time, t.trial_accept AS target_trial_accept, t.trial_actual AS target_trial_actual, t.new_enroll AS target_new_enroll,
                r.call , SEC_TO_TIME(r.talk_time) AS talk_time , r.trial_accept , r.trial_actual, r.new_enroll, r.collection
            FROM users AS u 
                LEFT JOIN cms_report_week_sale_hub AS r ON r.user_id=u.id
                LEFT JOIN cms_report_target AS t ON t.user_id=u.id AND t.report_week_id=r.report_week_id
            WHERE u.status=1 AND $cond AND (SELECT count(role_id) FROM model_has_roles WHERE model_id=u.id AND role_id=4)>0
            ORDER BY u.id DESC $limitation");
            
        $data = u::makingPagination($list, $total->total, $page, $limit);
        $tmp_report_week = u::first("SELECT CONCAT('Tuần từ ngày ',start_date,' đến ', end_date) AS label FROM cms_report_week WHERE id= $report_week_id");
        $data->label_report=$tmp_report_week->label;
        return response()->json($data);
    }
    public function report03(Request $request)
    {
        // call, talk_time
        u::query("UPDATE voip24h_data AS v
                LEFT JOIN cms_parents AS p ON p.mobile_1 = v.phone 
                LEFT JOIN users AS u ON u.sip_id = v.sip_id 
                SET v.parent_id =p.id ,v.user_id=u.id
            WHERE p.id IS NOT NULL  AND u.id IS NOT NULL AND v.parent_id IS NULL AND v.created_at >'".date('Y-m-d 00:00:00')."'");
        u::query("UPDATE voip24h_data AS v
                LEFT JOIN cms_parents AS p ON p.mobile_2 = v.phone 
                LEFT JOIN users AS u ON u.sip_id = v.sip_id 
                SET v.parent_id =p.id ,v.user_id=u.id
            WHERE p.id IS NOT NULL  AND u.id IS NOT NULL AND v.parent_id IS NULL AND v.created_at >'".date('Y-m-d 00:00:00')."'");
        $keyword = isset($request->keyword) ? $request->keyword : '';
        
        $pagination = (object)$request->pagination;
        $page = isset($pagination->cpage) ? (int) $pagination->cpage : 1;
        $limit = isset($pagination->limit) ? (int) $pagination->limit : 20;
        $offset = $page == 1 ? 0 : $limit * ($page-1);
        $limitation =  $limit > 0 ? " LIMIT $offset, $limit": "";
        $cond = "1";
        $cond1 = "1";
        if($keyword!==''){
            $cond .= " AND (u.name LIKE '%$keyword%' OR u.hrm_id LIKE '%$keyword%')";
        }
        $cond2 ="";
        if(in_array(1,$request->type_status)){
            $cond2 .= $cond2 ? " OR disposition = 'ANSWERED'" : "disposition = 'ANSWERED'";
        }
        if(in_array(2,$request->type_status)){
            $cond2 .= $cond2 ? " OR disposition = 'NO ANSWER'" : "disposition = 'NO ANSWER'";
        }
        if(in_array(3,$request->type_status)){
            $cond2 .= $cond2 ? " OR disposition = 'BUSY'" : "disposition = 'BUSY'";
        }
        if($cond2){
            $cond1.=" AND ( $cond2 ) ";
        }
        if (!empty($request->branch_id)) {
            $cond .= " AND u.branch_id IN (".implode(",",$request->branch_id).")";
        }
        if($request->type_date == 1){
            $cond1 .= " AND start_time >= '".date('Y-m-d 00:00:00')."'";
        }elseif($request->type_date == 2){
            $cond1 .= " AND start_time < '".date('Y-m-d 00:00:00')."' AND start_time >= '".date('Y-m-d 00:00:00',strtotime ( '-1 day' , time() ) )."'";
        }elseif($request->type_date == 3){
            $cond1 .= " AND start_time >= '".date('Y-m-d 00:00:00',strtotime("last Monday"))."'";
        }elseif($request->type_date == 4){
            $cond1 .= " AND start_time < '".date('Y-m-d 00:00:00',strtotime("last Monday"))."' AND start_time >= '".date('Y-m-d 00:00:00',strtotime("last Monday")-24*7*3600)."'";
        }elseif($request->type_date == 5){
            $cond1 .= " AND start_time >= '".date('Y-m-01 00:00:00')."'";
        }elseif($request->type_date == 6){
            $cond1 .= " AND start_time < '".date('Y-m-01 00:00:00')."' AND start_time >= '".date('Y-m-01 00:00:00',strtotime('-1 month'))."'";
        }
        if(!$request->user()->hasRole('admin') && !$request->user()->hasRole('Supervisor')){
            $cond .= " AND u.id IN (".$request->user_info->users_manager.")";
        }
        $total = u::first("SELECT count(u.id) AS total FROM users AS u 
            WHERE u.status=1 AND $cond ");
        $list = u::query("SELECT CONCAT(u.sip_id,' - ',u.name,' - ',u.hrm_id) AS sip_name,
                (SELECT name FROM cms_branches WHERE id=u.branch_id) AS branch_name,
                (SELECT count(id) FROM voip24h_data WHERE user_id=u.id AND `type`='inbound' AND $cond1) AS total_inbound,
                (SELECT count(id) FROM voip24h_data WHERE user_id=u.id AND `type`='outbound' AND $cond1) AS total_outbound,
                (SELECT SUM(duration) FROM voip24h_data WHERE user_id=u.id AND `type`='inbound' AND $cond1) AS total_duration_inbound,
                (SELECT SUM(duration) FROM voip24h_data WHERE user_id=u.id AND `type`='outbound' AND $cond1) AS total_duration_outbound,
                0 As duration_inbound,
                0 AS duration_outbound
            FROM users AS u 
            WHERE u.status=1 AND $cond AND sip_id IS NOT NULL
            ORDER BY u.id DESC $limitation");
        foreach($list AS $k=>$row){
            $list[$k]->duration_inbound = gmdate("H:i:s", ($row->total_inbound ? $row->total_duration_inbound / $row->total_inbound :0));
            $list[$k]->duration_outbound = gmdate("H:i:s", ($row->total_outbound ? $row->total_duration_outbound/ $row->total_outbound :0));
            $list[$k]->total_duration_inbound = gmdate("H:i:s", $row->total_duration_inbound);
            $list[$k]->total_duration_outbound = gmdate("H:i:s", $row->total_duration_outbound);
        }
            
        $data = u::makingPagination($list, $total->total, $page, $limit);
        return response()->json($data);
    }
}
