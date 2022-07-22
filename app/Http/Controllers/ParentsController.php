<?php

namespace App\Http\Controllers;

use App\Models\LogParents;
use App\Models\Sms;
use App\Providers\UtilityServiceProvider as u;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $status = isset($request->status) ? $request->status : [];
        $keyword = isset($request->keyword) ? $request->keyword : '';
        $owner_id = isset($request->owner_id) ? $request->owner_id :  [];
        $source_id = isset($request->source_id) ? $request->source_id : [];
        $source_detail_id = isset($request->source_detail_id) ? $request->source_detail_id : [];
        $end_date = isset($request->end_date) ? $request->end_date : '';
        $start_date = isset($request->start_date) ? $request->start_date : '';
        $type_seach = isset($request->type_seach) ? $request->type_seach : 0;

        $pagination = (object)$request->pagination;
        $page = isset($pagination->cpage) ? (int) $pagination->cpage : 1;
        $limit = isset($pagination->limit) ? (int) $pagination->limit : 20;
        $offset = $page == 1 ? 0 : $limit * ($page-1);
        $limitation =  $limit > 0 ? " LIMIT $offset, $limit": "";
        $cond = " 1 ";
        if(!$request->user()->hasRole('admin') && !$request->user()->hasRole('Supervisor') && !$request->user()->hasRole('Marketing')){
            $cond .= " AND p.owner_id IN (".$request->user_info->users_manager.")";
        }
        if($request->user()->hasRole('Marketing')){
            $cond .= " AND p.creator_id IN (".$request->user()->id.")";
        }
        if (!empty($status)) {
            $cond .= " AND p.status IN (".implode(",",$status).")";
        }
        if (!empty($owner_id)) {
            $cond .= " AND p.owner_id IN (".implode(",",$owner_id).")" ;
        }
        if (!empty($source_id)) {
            $cond .= " AND p.source_id IN (".implode(",",$source_id).")";
        }
        if (!empty($source_detail_id)) {
            $cond .= " AND p.source_detail_id IN (".implode(",",$source_detail_id).")";
        }
        
        if ($keyword !== '') {
            $cond .= " AND (p.name LIKE '%$keyword%' OR p.mobile_1 LIKE '%$keyword%' OR p.mobile_2 LIKE '%$keyword%') ";
        }
        if ($end_date !== '') {
            $cond .= " AND p.next_care_date < '$end_date 23:59:59' AND p.next_care_date IS NOT NULL";
        }
        if ($start_date !== '') {
            $cond .= " AND p.next_care_date > '$start_date 00:00:00'";
        }
        //type_search=1
        $cond_1 = " AND (SELECT count(id) FROM cms_customer_care WHERE parent_id=p.id AND status=1)=0 AND p.status != 8 ";
        //type_search=2
        $cond_2 = " AND DATE_FORMAT(next_care_date,'%Y-%m-%d') = '".date('Y-m-d')."'";
        $cond_3 = " AND DATE_FORMAT(next_care_date,'%Y-%m-%d') < '".date('Y-m-d')."' 
            AND ( SELECT count(id) FROM cms_customer_care WHERE parent_id=p.id AND care_date >= next_care_date AND status=1 )=0 AND p.status NOT IN (8,9,10,12)";
        $order_by = " ORDER BY p.id DESC ";
        $tmp_cond="";
        if($type_seach==1){
            $tmp_cond = $cond_1;
        }elseif($type_seach==2){
            $tmp_cond = $cond_2;
            $order_by = " ORDER BY next_care_date ASC ";
        }elseif($type_seach==3){
            $tmp_cond = $cond_3;
            $order_by = " ORDER BY next_care_date ASC ";
        }
        $total = u::first("SELECT count(id) AS total FROM cms_parents AS p WHERE $cond $tmp_cond");
        $list = u::query("SELECT p.*, (SELECT name FROM cms_sources WHERE id=p.source_id) AS source_name,
                (SELECT name FROM cms_source_detail WHERE id=p.source_detail_id) AS source_detail_name,
                (SELECT note FROM cms_customer_care WHERE parent_id=p.id AND status=1 ORDER BY care_date DESC LIMIT 1) AS last_care,
                (SELECT care_date FROM cms_customer_care WHERE parent_id=p.id AND status=1 ORDER BY care_date DESC LIMIT 1) AS last_time_care,
                (SELECT name FROM users WHERE id=p.owner_id) AS owner_name ,
                (SELECT name FROM cms_students WHERE parent_id=p.id LIMIT 0,1) AS hs1_name,
                (SELECT name FROM cms_students WHERE parent_id=p.id LIMIT 1,1) AS hs2_name
            FROM cms_parents AS p WHERE $cond $tmp_cond $order_by $limitation");
        $data = u::makingPagination($list, $total->total, $page, $limit);

        $total_0 = u::first("SELECT count(id) AS total FROM cms_parents AS p WHERE $cond ");
        $total_1 = u::first("SELECT count(id) AS total FROM cms_parents AS p WHERE $cond $cond_1 ");
        $total_2 = u::first("SELECT count(id) AS total FROM cms_parents AS p WHERE $cond $cond_2 ");
        $total_3 = u::first("SELECT count(id) AS total FROM cms_parents AS p WHERE $cond $cond_3 ");
        $data->detail_total = (object)array(
            'total_0' => $total_0->total,
            'total_1' => $total_1->total,
            'total_2' => $total_2->total,
            'total_3' => $total_3->total,
        );
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
            'source_detail_id' => $request->source_detail_id,
            'note' => $request->note,
            'created_at' => date('Y-m-d H:i:s'),
            'creator_id' => Auth::user()->id,
            'last_assign_date' => date('Y-m-d H:i:s'),
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
            'source_detail_id' => $request->source_detail_id,
            'note' => $request->note,
            'updated_at' => date('Y-m-d H:i:s'),
            'updator_id' => Auth::user()->id,
            'owner_id'=>$request->owner_id,
            'last_assign_date'=>$request->owner_id != $pre_parent_info->owner_id ? date('Y-m-d H:i:s') : $pre_parent_info->last_assign_date,
            'status'=>$request->status,
            'c2c_mobile'=>$request->c2c_mobile,
        );
        $data = u::updateSimpleRow($data_update, array('id' => $parent_id), 'cms_parents');
        LogParents::logUpdateInfo($pre_parent_info,$data_update,Auth::user()->id);
        StudentsController::updateParentCRM($parent_id);
        return response()->json($data);
    }
    public function assign(Request $request)
    {
        $pre_parent_info = u::first("SELECT owner_id,last_assign_date FROM cms_parents WHERE id=$request->parent_id");
        $data = u::updateSimpleRow(array(
            'updated_at' => date('Y-m-d H:i:s'),
            'updator_id' => Auth::user()->id,
            'owner_id'=>$request->owner_id,
            'last_assign_date'=>$request->owner_id != $pre_parent_info->owner_id ? date('Y-m-d H:i:s') : $pre_parent_info->last_assign_date,
        ), array('id' => $request->parent_id), 'cms_parents');
        LogParents::logAssign($request->parent_id,$pre_parent_info->owner_id,$request->owner_id,Auth::user()->id);
        return response()->json($data);
    }
    public function assignList(Request $request)
    {
        $cond = implode(",",$request->parents);
        $arr_owner = $request->owners;
        $list_parent_info = u::query("SELECT p.id AS parent_id,p.owner_id,(SELECT CONCAT(name,' (',hrm_id,')') FROM users WHERE id= p.owner_id) AS pre_owner,p.last_assign_date FROM cms_parents AS p WHERE p.id IN ($cond)");
        foreach($list_parent_info AS $k=>$row){
            $owner_id =  $arr_owner[$k%count($arr_owner)];
            $last_assign_date = $owner_id != $row->owner_id ? date('Y-m-d H:i:s') : $row->last_assign_date;
            u::query("UPDATE cms_parents SET owner_id= $owner_id,last_assign_date='$last_assign_date' WHERE id =$row->parent_id");
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
    public function show(Request $request,$parent_id)
    {
        $cond="";
        if(!$request->user()->hasRole('admin') && !$request->user()->hasRole('Supervisor') && !$request->user()->hasRole('Marketing')){
            $cond .= " AND p.owner_id IN (".$request->user_info->users_manager.")";
        }
        $data = u::first("SELECT p.*,(SELECT name FROM users WHERE id=p.creator_id) AS creator_name,
                (SELECT name FROM cms_districts WHERE id=p.district_id) AS district_name,
                (SELECT name FROM cms_provinces WHERE id=p.province_id) AS province_name,
                (SELECT name FROM cms_sources WHERE id=p.source_id) AS source_name,
                (SELECT name FROM cms_source_detail WHERE id=p.source_detail_id) AS source_detail_name,
                (SELECT title FROM cms_jobs WHERE id=p.job_id) AS job_name,
                (SELECT count(id) FROM cms_customer_care WHERE parent_id=p.id AND status=1) AS num_care,
                (SELECT care_date FROM cms_customer_care WHERE parent_id=p.id  AND status=1 ORDER BY care_date DESC LIMIT 1) AS last_care
            FROM cms_parents AS p WHERE id=$parent_id  $cond");
        if($data){
            if(!($request->user()->hasRole('admin') || $request->user()->hasRole('Salehub') || $request->user()->hasRole('Leader'))){
                $data->branch_id= $request->user()->branch_id;
            }else{
                $data->branch_id=0;
            }
            $data->next_care_date =  $data->next_care_date ? date("Y-m-d\TH:i", strtotime($data->next_care_date)): $data->next_care_date;
        }
        return response()->json($data);
    }
    public function delete($parent_id)
    {
        $data = u::query("DELETE FROM cms_parents WHERE id=$parent_id");
        return response()->json($data);
    }
    public function getLogs(Request $request, $parent_id){
        $check_role = 0 ;
        if($request->user()->hasRole('admin')  || in_array($request->user()->id,[39,40,152])){
            $check_role = 1 ;
        }
        $data = u::query("SELECT l.*,(SELECT name FROM users WHERE id=l.creator_id) AS creator_name
            FROM cms_parent_logs AS l WHERE l.parent_id=$parent_id  
                AND (l.status=1 OR (l.status=0 AND 1=$check_role))
            ORDER BY l.id DESC");
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
            $duplicate_info = u::first("SELECT p.is_lock,u.name,u.hrm_id, u.branch_name, 
                    (SELECT care_date FROM cms_customer_care WHERE parent_id=p.id AND status=1 ORDER BY care_date DESC LIMIT 1) AS care_date,
                    (SELECT count(id) FROM cms_customer_care WHERE parent_id=p.id AND status=1 ) AS total_care, p.last_assign_date
                FROM cms_parents AS p LEFT JOIN users AS u ON u.id=p.owner_id  
                WHERE (p.mobile_1='$phone' OR p.mobile_2='$phone') AND p.id!='$parent_id'");
            if($duplicate_info){
                $result->status = 0;
                $result->message = "Khách hàng có SĐT: $phone đang thuộc quyền quản lý của nhân viên $duplicate_info->name - $duplicate_info->hrm_id $duplicate_info->branch_name";
            }
            // $parent_info = u::first("SELECT mobile_1,mobile_2 FROM cms_parents WHERE id=$parent_id");
            // if($parent_info->mobile_1!=$phone && $parent_info->mobile_2!=$phone){
            //     $connection = DB::connection('mysql_crm');
            //     $lead_info = $connection->select(DB::raw("SELECT u.full_name AS ec_name,u.hrm_id AS ec_hrm_id,(SELECT name FROM branches WHERE id=t.branch_id) AS branch_name
            //         FROM students AS s LEFT JOIN term_student_user AS t ON t.student_id =s.id LEFT JOIN users AS u ON u.id=t.ec_id 
            //             WHERE s.gud_mobile1='{$phone}' OR  s.gud_mobile2='{$phone}'"));
            //     if(isset($lead_info[0])){
            //         $lead_info = $lead_info[0];
            //         $result->status = 0;
            //         $result->message = "Khách hàng có SĐT: $phone đang thuộc quyền quản lý của nhân viên $lead_info->ec_name - $lead_info->ec_hrm_id $lead_info->branch_name";
            //     }
            // }
        }else{
            $duplicate_info = u::first("SELECT p.is_lock,u.name,u.hrm_id, u.branch_name,p.status,u.branch_id,
                    (SELECT care_date FROM cms_customer_care WHERE parent_id=p.id AND status=1 AND creator_id=p.owner_id ORDER BY care_date DESC LIMIT 1) AS care_date,
                    (SELECT count(id) FROM cms_customer_care WHERE parent_id=p.id AND status=1  AND creator_id=p.owner_id) AS total_care, p.last_assign_date
                FROM cms_parents AS p LEFT JOIN users AS u ON u.id=p.owner_id  WHERE (p.mobile_1='$phone' OR p.mobile_2='$phone') ");
            if($duplicate_info){
                if($duplicate_info->is_lock==0){
                    $result->status = 2;
                    $result->message = "Khách hàng có SĐT: $phone đã tồn tại trên hệ thống, bạn có muốn chăm sóc?";
                }else{
                    $result->status = 0;
                    $text = "";
                    if(in_array($duplicate_info->branch_id,[5,9])){
                        $thoi_gian_con = 60;
                        $text.="<br> Thời gian chăm sóc gần nhất: ".date('Y-m-d H:i:s',time()-4800)." <br> Thời gian còn lại sẽ được ghi đè sau $thoi_gian_con ngày";
                    }elseif($duplicate_info->total_care>0){
                        $thoi_gian_con = 60 - ceil((time() - strtotime($duplicate_info->care_date))/(3600*24));
                        $text.="<br> Thời gian chăm sóc gần nhất: $duplicate_info->care_date <br> Thời gian còn lại sẽ được ghi đè sau $thoi_gian_con ngày";
                    }else{
                        $thoi_gian_con = 15 - ceil((time() - strtotime($duplicate_info->last_assign_date))/(3600*24));
                        $text.="<br> Thời gian còn lại sẽ được ghi đè sau $thoi_gian_con ngày";
                    }
                    if(in_array($duplicate_info->status,[12,8,9,10])){
                        $text.="<br> Khách hàng thuộc các trường hợp không được phép ghi đè - ".u::getStatus($duplicate_info->status);
                    }
                    $result->message = "Khách hàng có SĐT: $phone đang thuộc quyền quản lý của nhân viên $duplicate_info->name - $duplicate_info->hrm_id $duplicate_info->branch_name .".$text;
                }
            }
            // $connection = DB::connection('mysql_crm');
            // $lead_info = $connection->select(DB::raw("SELECT u.full_name AS ec_name,u.hrm_id AS ec_hrm_id,(SELECT name FROM branches WHERE id=t.branch_id) AS branch_name
            //     FROM students AS s LEFT JOIN term_student_user AS t ON t.student_id =s.id LEFT JOIN users AS u ON u.id=t.ec_id 
            //         WHERE s.gud_mobile1='{$phone}' OR  s.gud_mobile2='{$phone}'"));
            // if(isset($lead_info[0])){
            //     $lead_info = $lead_info[0];
            //     $result->status = 0;
            //     $result->message = "Khách hàng có SĐT: $phone đang thuộc quyền quản lý của nhân viên $lead_info->ec_name - $lead_info->ec_hrm_id $lead_info->branch_name";
            // }
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
                'last_assign_date'=> date('Y-m-d H:i:s'),
                'is_lock'=>1,
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
            $sms = new Sms();
            $res = $sms->sendSms($phone,$request->content,$request->user()->id);
            $res = json_decode($res,true);
            u::insertSimpleRow( array(
                'parent_id'=>$parent_info->id,
                'note'=>$request->content,
                'created_at'=>date('Y-m-d H:i:s'),
                'creator_id'=>$request->user()->id,
                'method_id'=>4,
                'care_date'=>date('Y-m-d H:i:s'),
                'phone'=>$phone ,
                'data_state'=> isset($res['CodeResult']) && $res['CodeResult']==100 ? 'Thành công' : 'Thất bại'
            ),'cms_customer_care');
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
    public function dashboard(Request $request)
    {
        $owner_id = isset($request->owner_id) ? $request->owner_id : '';
        $end_date = isset($request->end_date) ? $request->end_date : '';
        $start_date = isset($request->start_date) ? $request->start_date : '';

        $pagination = (object)$request->pagination;
        $page = isset($pagination->cpage) ? (int) $pagination->cpage : 1;
        $limit = isset($pagination->limit) ? (int) $pagination->limit : 20;
        $offset = $page == 1 ? 0 : $limit * ($page-1);
        $limitation =  $limit > 0 ? " LIMIT $offset, $limit": "";
        $cond = " 1 ";
        $cond_sub = " 1 ";
        if(!$request->user()->hasRole('admin')){
            $cond .= " AND u.id IN (".$request->user_info->users_manager.")";
        }
        if ($owner_id !== '') {
            $cond .= " AND u.id = $owner_id ";
        }
        if ($end_date !== '') {
            $cond_sub .= " AND start_time < '$end_date 23:59:59' ";
        }
        if ($start_date !== '') {
            $cond_sub .= " AND start_time > '$start_date 00:00:00'";
        }

        $sql_tongcuocgoi = " (SELECT count(id) FROM voip24h_data WHERE user_id=u.id)";
        $sql_traloi = "(SELECT count(id) FROM voip24h_data WHERE user_id=u.id AND (disposition = 'NO ANSWER' OR disposition = 'BUSY'))";
        $sql_khongtraloi = "(SELECT count(id) FROM voip24h_data WHERE user_id=u.id AND (disposition = 'NO ANSWER' OR disposition = 'BUSY') AND type='outbound')";
        $sql_goinho = "(SELECT count(id) FROM voip24h_data WHERE user_id=u.id AND (disposition = 'NO ANSWER' OR disposition = 'BUSY') AND type='inbound')";
        $sql_vao = "(SELECT count(id) FROM voip24h_data WHERE user_id=u.id AND type='inbound')";
        $sql_ra = "(SELECT count(id) FROM voip24h_data WHERE user_id=u.id AND type='outbound')";
        $total = u::first("SELECT count(u.id) AS total FROM users AS u WHERE $cond AND u.sip_id IS NOT NULL AND u.sip_id!=0");
        $list = u::query("SELECT u.id, u.name, u.hrm_id, u.branch_name ,
                $sql_tongcuocgoi AS tongcuocgoi,
                $sql_traloi AS traloi,
                $sql_khongtraloi AS khongtraloi,
                $sql_goinho AS goinho,
                $sql_vao AS vao,
                $sql_ra AS ra
            FROM users AS u WHERE $cond AND u.sip_id IS NOT NULL AND u.sip_id!=0 ORDER BY u.id DESC $limitation");
        $data = u::makingPagination($list, $total->total, $page, $limit);

        $cond_total = " 1 ";
        if(!$request->user()->hasRole('admin')){
            $cond_total .= " AND user_id IN (".$request->user_info->users_manager.")";
        }
        //Tổng cuộc gọi
        $total_all = u::first("SELECT count(id) AS total FROM voip24h_data WHERE $cond_total");
        $total_all_duration = u::first("SELECT SUM(duration) AS duration FROM voip24h_data WHERE $cond_total AND disposition = 'ANSWERED'");
        $total_traloi = u::first("SELECT count(id) AS total FROM voip24h_data WHERE $cond_total AND disposition = 'ANSWERED'");
        $total_khongtraloi = u::first("SELECT count(id) AS total FROM voip24h_data WHERE $cond_total AND (disposition = 'NO ANSWER' OR disposition = 'BUSY') AND type='outbound'");
        $total_goinho = u::first("SELECT count(id) AS total FROM voip24h_data WHERE $cond_total AND (disposition = 'NO ANSWER' OR disposition = 'BUSY') AND type='inbound'");
        $total_vao = u::first("SELECT count(id) AS total FROM voip24h_data WHERE $cond_total AND type='inbound'");
        $total_ra = u::first("SELECT count(id) AS total FROM voip24h_data WHERE $cond_total AND type='outbound'");
        $total_vao_duration = u::first("SELECT SUM(duration) AS duration AS total FROM voip24h_data WHERE $cond_total AND type='inbound' AND disposition = 'ANSWERED'");
        $total_ra_duration = u::first("SELECT SUM(duration) AS duration AS total FROM voip24h_data WHERE $cond_total AND type='outbound' AND disposition = 'ANSWERED'");

        $data->dashboard = (object)array(
            'total_all' =>$total_all->total,
            'total_all_duration' =>$total_all_duration->duration,
            'total_traloi' =>$total_traloi->total,
            'total_khongtraloi' =>$total_khongtraloi->total,
            'total_goinho' =>$total_goinho->total,
            'total_vao' =>$total_vao->total,
            'total_ra' =>$total_ra->total,
            'total_vao_duration' =>$total_vao_duration->duration,
            'total_ra_duration' =>$total_ra_duration->duration,
        );
        return response()->json($data);
    }
    public function processParentLock(){
        u::query("UPDATE cms_parents AS p LEFT JOIN users AS u ON u.id = p.owner_id SET p.tmp_branch_id = u.branch_id");
        u::query("UPDATE cms_parents AS p SET p.last_care_date=(SELECT care_date FROM cms_customer_care WHERE parent_id=p.id AND creator_id=p.owner_id ORDER BY id DESC LIMIT 1) WHERE p.is_lock=1 AND p.status NOT IN(12,9,8,10)");
        u::query("UPDATE cms_parents SET is_lock = 0 
            WHERE last_care_date IS NULL 
                AND last_assign_date IS NOT NULL 
                AND is_lock=1 AND status NOT IN(12,9,8,10)
                AND tmp_branch_id NOT IN (5,9)
                AND DATEDIFF( CURRENT_DATE, last_assign_date )> 15");
        u::query("UPDATE cms_parents SET is_lock = 0 
            WHERE
                last_care_date IS NOT NULL 
                AND last_assign_date IS NOT NULL 
                AND tmp_branch_id NOT IN (5,9)
                AND is_lock=1 AND status NOT IN(12,9,8,10)
                AND DATEDIFF( CURRENT_DATE, last_care_date )> 60");
        return "ok";
    }
}
