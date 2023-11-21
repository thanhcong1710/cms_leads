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
        // if($request->user()->id==21){
        //     $cond .= " AND ((p.owner_id IN (".$request->user_info->users_manager.") AND p.owner_id NOT IN (".$request->user_info->tmp_users_manager.")) OR p.source_id=27 OR p.source_id=35 OR p.source_id=26)";
        // }else
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
                (SELECT DATE_FORMAT(care_date,'%Y-%m-%d') FROM cms_customer_care WHERE parent_id =p.id AND status=1 ORDER BY id DESC LIMIT 1) AS last_care_date,
                (SELECT t.name FROM cms_customer_care AS c LEFT JOIN cms_contact_methods AS t ON t.id=c.method_id WHERE c.parent_id =p.id AND c.status=1 ORDER BY c.id DESC LIMIT 1) AS last_method,
                (SELECT count(c.id) FROM cms_customer_care AS c WHERE c.parent_id =p.id AND c.status=1 ) AS total_care
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
    public function report04(Request $request)
    {
        // call, talk_time
        u::query("UPDATE voip24h_data AS v
                LEFT JOIN cms_parents AS p ON p.mobile_1 = v.phone 
                LEFT JOIN users AS u ON u.sip_id = v.sip_id 
                SET v.parent_id =p.id ,v.user_id=u.id, v.branch_id=u.branch_id
            WHERE v.user_id IS NULL AND v.created_at >'".date('Y-m-d 00:00:00')."'");
        u::query("UPDATE voip24h_data AS v
                LEFT JOIN cms_parents AS p ON p.mobile_2 = v.phone 
                LEFT JOIN users AS u ON u.sip_id = v.sip_id 
                SET v.parent_id =p.id ,v.user_id=u.id, v.branch_id=u.branch_id
            WHERE v.user_id IS NULL AND v.created_at >'".date('Y-m-d 00:00:00')."'");
        u::query("UPDATE voip24h_data AS v
            LEFT JOIN cms_customer_care AS c ON c.data_id = v.id 
            SET v.STATUS = c.STATUS WHERe v.status!=c.status");
        $keyword = isset($request->keyword) ? $request->keyword : '';
        
        $pagination = (object)$request->pagination;
        $page = isset($pagination->cpage) ? (int) $pagination->cpage : 1;
        $limit = isset($pagination->limit) ? (int) $pagination->limit : 20;
        $offset = $page == 1 ? 0 : $limit * ($page-1);
        $limitation =  $limit > 0 ? " LIMIT $offset, $limit": "";
        $cond = "1";
        $cond2 = "";
        if($keyword!==''){
            $cond .= " AND (u.name LIKE '%$keyword%' OR u.hrm_id LIKE '%$keyword%' OR v.phone LIKE '%$keyword%')";
        }
        if(in_array(1,$request->type_status)){
            $cond2 .= $cond2 ? " OR v.disposition = 'ANSWERED'" : "v.disposition = 'ANSWERED'";
        }
        if(in_array(2,$request->type_status)){
            $cond2 .= $cond2 ? " OR v.disposition = 'NO ANSWER'" : "v.disposition = 'NO ANSWER'";
        }
        if(in_array(3,$request->type_status)){
            $cond2 .= $cond2 ? " OR v.disposition = 'BUSY'" : "v.disposition = 'BUSY'";
        }
        if($cond2){
            $cond.=" AND ( $cond2 ) ";
        }
        if (!empty($request->branch_id)) {
            $cond.= " AND u.branch_id IN (".implode(",",$request->branch_id).")";
        }
        if($request->type_call ){
            $cond.=$request->type_call ==2 ? " AND v.type='inbound' " : " AND v.type='outbound'";
        }
        if($request->from_date){
            $request->type_date = 0;
            $cond .= " AND v.start_time >= '".date('Y-m-d H:i:s',strtotime($request->from_date))."'";
        }
        if($request->to_date){
            $request->type_date = 0;
            $cond .= " AND v.start_time <= '".date('Y-m-d H:i:s',strtotime($request->to_date))."'";
        }
        if($request->type_date == 1){
            $cond .= " AND v.start_time >= '".date('Y-m-d 00:00:00')."'";
        }elseif($request->type_date == 2){
            $cond .= " AND v.start_time < '".date('Y-m-d 00:00:00')."' AND v.start_time >= '".date('Y-m-d 00:00:00',strtotime ( '-1 day' , time() ) )."'";
        }elseif($request->type_date == 3){
            $cond .= " AND v.start_time >= '".date('Y-m-d 00:00:00',strtotime("last Monday"))."'";
        }elseif($request->type_date == 4){
            $cond .= " AND v.start_time < '".date('Y-m-d 00:00:00',strtotime("last Monday"))."' AND v.start_time >= '".date('Y-m-d 00:00:00',strtotime("last Monday")-24*7*3600)."'";
        }elseif($request->type_date == 5){
            $cond .= " AND v.start_time >= '".date('Y-m-01 00:00:00')."'";
        }elseif($request->type_date == 6){
            $cond .= " AND v.start_time < '".date('Y-m-01 00:00:00')."' AND v.start_time >= '".date('Y-m-01 00:00:00',strtotime('-1 month'))."'";
        }
        // if($request->user()->id==21){
        //     $cond .= " AND (u.id IN (".$request->user_info->users_manager.") AND u.id NOT IN (".$request->user_info->tmp_users_manager.")) ";
        // }else
        if(!$request->user()->hasRole('admin') && !$request->user()->hasRole('Supervisor')){
            $cond .= " AND u.id IN (".$request->user_info->users_manager.")";
        }
        $total = u::first("SELECT count(v.id) AS total FROM voip24h_data AS v
                LEFT JOIN users AS u ON u.id=v.user_id  
            WHERE v.status=1 AND v.sip_id IS NOT NULL AND $cond  ");
        $list = u::query("SELECT v.start_time,
                IF(v.type='inbound',v.phone, CONCAT(v.sip_id,' - ',u.name,' - ',u.hrm_id)) AS phone_call,
                IF(v.type='inbound',CONCAT(v.sip_id,' - ',u.name,' - ',u.hrm_id), v.phone) AS phone_rep,
                v.duration, IF(v.type='inbound','Gọi vào','Gọi ra') AS phone_type, v.disposition AS phone_status,
                (SELECT name FROM cms_branches WHERE id=v.branch_id) AS branch_name, v.link_record
            FROM voip24h_data AS v
                LEFT JOIN users AS u ON u.id=v.user_id
            WHERE v.status=1 AND v.sip_id IS NOT NULL AND $cond 
            ORDER BY v.id DESC $limitation");
        $arr_status = [
            'NO ANSWER'=>'Không nghe máy',
            'BUSY'=>'Máy bận',
            'ANSWERED'=>'Nghe máy'
        ];
        foreach($list AS $k=>$row){
            $list[$k]->duration = gmdate("H:i:s", $row->duration);
            $list[$k]->phone_status = isset($arr_status[$list[$k]->phone_status]) ? $arr_status[$list[$k]->phone_status] : $list[$k]->phone_status;
        }
            
        $data = u::makingPagination($list, $total->total, $page, $limit);
        return response()->json($data);
    }

    public function report03(Request $request)
    {
        $cond = "";
        $cond1 = "";
       
        if (!empty($request->branch_id)) {
            $cond .= " AND u.branch_id = ".$request->branch_id;
            $cond1 .= " AND u.branch_id = ".$request->branch_id;
        }
        if($request->start_date){
            $cond .= " AND p.last_assign_date >= '".date('Y-m-d 00:00:00',strtotime($request->start_date))."'";
            $cond1 .= " AND c.created_at >= '".date('Y-m-d 00:00:00',strtotime($request->start_date))."'";
        }
        if($request->end_date){
            $cond .= " AND p.last_assign_date <= '".date('Y-m-d 00:00:00',strtotime($request->end_date))."'";
            $cond1 .= " AND c.created_at <= '".date('Y-m-d 00:00:00',strtotime($request->end_date))."'";
        }
        if (!empty($request->owner_id)) {
            $cond .= " AND  u.id IN (".implode(",",$request->owner_id).")" ;
            $cond1 .= " AND  u.id IN (".implode(",",$request->owner_id).")" ;
        }
        if (!empty($request->source_id)) {
            $cond .= " AND p.source_id IN (".implode(",",$request->source_id).")";
            $cond1 .= " AND p.source_id IN (".implode(",",$request->source_id).")";
        }
        if (!empty($request->source_detail_id)) {
            $cond .= " AND p.source_detail_id IN (".implode(",",$request->source_detail_id).")";
            $cond1 .= " AND p.source_detail_id IN (".implode(",",$request->source_detail_id).")";
        }
        
       
        if(!$request->user()->hasRole('admin') && !$request->user()->hasRole('Supervisor')){
            $cond .= " AND u.id IN (".$request->user_info->users_manager.")";
            $cond1 .= " AND u.id IN (".$request->user_info->users_manager.")";
        }
        $data = u::first("SELECT (SELECT COUNT(p.id) FROM cms_parents AS p LEFT JOIN users AS u ON u.id=p.owner_id WHERE p.status=0 AND (p.last_care_date ='' OR  p.last_care_date IS NULL) $cond ) AS total_new,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id LEFT JOIN users AS u ON u.id=c.creator_id WHERE c.call_status IN (4,5,6,7) $cond) AS total_connect,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id LEFT JOIN users AS u ON u.id=c.creator_id WHERE c.call_status IN (1,2,3) $cond) AS total_not_connect,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id LEFT JOIN users AS u ON u.id=c.creator_id WHERE c.call_status = 0 $cond) AS detail_0,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id LEFT JOIN users AS u ON u.id=c.creator_id WHERE c.call_status = 1 $cond) AS detail_1,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id LEFT JOIN users AS u ON u.id=c.creator_id WHERE c.call_status = 2 $cond) AS detail_2,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id LEFT JOIN users AS u ON u.id=c.creator_id WHERE c.call_status = 3 $cond) AS detail_3,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id LEFT JOIN users AS u ON u.id=c.creator_id WHERE c.call_status = 4 $cond) AS detail_4,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id LEFT JOIN users AS u ON u.id=c.creator_id WHERE c.call_status = 5 $cond) AS detail_5,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id LEFT JOIN users AS u ON u.id=c.creator_id WHERE c.call_status = 6 $cond) AS detail_6,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id LEFT JOIN users AS u ON u.id=c.creator_id WHERE c.call_status = 7 $cond) AS detail_7,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id LEFT JOIN users AS u ON u.id=c.creator_id WHERE c.call_status = 5 AND c.call_status_sub = 51 $cond) AS detail_51,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id LEFT JOIN users AS u ON u.id=c.creator_id WHERE c.call_status = 5 AND c.call_status_sub = 52 $cond) AS detail_52,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id LEFT JOIN users AS u ON u.id=c.creator_id WHERE c.call_status = 5 AND c.call_status_sub = 53 $cond) AS detail_53,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id LEFT JOIN users AS u ON u.id=c.creator_id WHERE c.call_status = 6 AND c.call_status_sub = 61 $cond) AS detail_61,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id LEFT JOIN users AS u ON u.id=c.creator_id WHERE c.call_status = 6 AND c.call_status_sub = 62 $cond) AS detail_62,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id LEFT JOIN users AS u ON u.id=c.creator_id WHERE c.call_status = 7 AND c.call_status_sub = 71 $cond) AS detail_71,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id LEFT JOIN users AS u ON u.id=c.creator_id WHERE c.call_status = 7 AND c.call_status_sub = 72 $cond) AS detail_72,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id LEFT JOIN users AS u ON u.id=c.creator_id WHERE c.call_status = 7 AND c.call_status_sub = 73 $cond) AS detail_73,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id LEFT JOIN users AS u ON u.id=c.creator_id WHERE c.call_status = 7 AND c.call_status_sub = 74 $cond) AS detail_74
                ");
            
        return response()->json($data);
    }
}
