<?php

namespace App\Http\Controllers;

use App\Providers\UtilityServiceProvider as u;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToolsController extends Controller
{
    public function downloadReCord()
    {
        $last_time = date('Y-m-d H:i:s',time()-3600);
        $list_call = u::query("SELECT * FROM voip24h_data WHERE process_data = 0 AND created_at>'$last_time' ORDER BY id DESC");
        foreach($list_call AS $row){
            $voipControll = new VoipController();
            $response = $voipControll->getDataCallId($row->callid);
            $data = json_decode($response);
            if(isset($data->result->data[0]->download) && $data->result->data[0]->download){
                $dir = __DIR__.'/../../../public/static/voip/'. date('Y_m').'/';
                $dir_file = 'static/voip/'. date('Y_m').'/';
                if(!file_exists($dir)){
                    mkdir($dir);
                }
                $file_name = $row->callid.".wav";
                $file_name_mp3 = $row->callid.".mp3";
                if(!file_exists($dir.$file_name)){
                    file_put_contents($dir.$file_name, fopen($data->result->data[0]->download, 'r'));
                    shell_exec('ffmpeg -i ' . $dir.$file_name . ' ' . $dir.$file_name_mp3 . ''); 
                    u::updateSimpleRow(array('meta_data'=>$response,'process_data'=>1,'link_record'=>$dir_file.$file_name_mp3),array('callid'=>$row->callid),'voip24h_data');
                }
            }
        }
        return "ok";
    }
    public function genWeek(){
        $year_start_date = '2022-01-01';
        $year_end_date = '2022-12-31';
        $start_date = $year_start_date;
        $end_date = $start_date; 
        for($i=0;$i<53;$i++){
            $start_date = date('Y-m-d',strtotime("next monday", strtotime($start_date)));
            $end_date = date('Y-m-d', (strtotime($start_date)+24*3600*6));
            echo $start_date."/";
            $title = "Tuần ".($i+2);
            if($end_date<$year_end_date){
                u::query("INSERT INTO cms_report_week (title,start_date,end_date,month,year,created_at) VALUES ('$title','$start_date','$end_date','".date('m',strtotime($start_date))."','".date('Y',strtotime($start_date))."','".date('Y-m-d H:i:s')."')");
            }

            $start_date = $start_date < $year_start_date ? $year_start_date : $start_date;
        }
        return "ok";
    }
    public function processReportSaleHub($report_week_id){
        $report_week_info = u::first("SELECT * FROM cms_report_week WHERE id=$report_week_id");
        // call, talk_time
        u::query("UPDATE voip24h_data AS v
                LEFT JOIN cms_parents AS p ON p.mobile_1 = v.phone 
                LEFT JOIN users AS u ON u.sip_id = v.sip_id 
                SET v.parent_id =p.id ,v.user_id=u.id
            WHERE p.id IS NOT NULL  AND u.id IS NOT NULL AND v.parent_id IS NULL AND v.created_at >'".$report_week_info->start_date." 00:00:00'");
        u::query("UPDATE voip24h_data AS v
                LEFT JOIN cms_parents AS p ON p.mobile_2 = v.phone 
                LEFT JOIN users AS u ON u.sip_id = v.sip_id 
                SET v.parent_id =p.id ,v.user_id=u.id
            WHERE p.id IS NOT NULL  AND u.id IS NOT NULL AND v.parent_id IS NULL AND v.created_at >'".$report_week_info->start_date." 00:00:00'");
        
        u::query("DELETE FROM cms_tmp_student WHERE report_week_id = $report_week_info->id");
        // Trial actual
        $list_trial_actual = u::queryCRM("SELECT s.update_checked AS finish_date,s.id AS student_crm_id, 0 AS amount
            FROM
                students AS s
            WHERE
                s.update_checked >='".$report_week_info->start_date."' AND s.update_checked <='".$report_week_info->end_date."'");
        $this->addItemsTmpStudent($list_trial_actual,$report_week_info->id,1);

        // New Enroll
        $list_new_enroll = u::queryCRM("SELECT p.charge_date AS finish_date,c.student_id AS student_crm_id, 0 AS amount
            FROM
                payment AS p
                LEFT JOIN contracts AS c ON c.id = p.contract_id 
            WHERE
                c.count_recharge = 0 
                AND ( SELECT count( id ) FROM payment WHERE contract_id = c.id AND charge_date > p.charge_date )= 0 
                AND p.charge_date >='".$report_week_info->start_date."' AND p.charge_date <='".$report_week_info->end_date."'");
        $this->addItemsTmpStudent($list_new_enroll,$report_week_info->id,2);
        // Collection
        $list_collection = u::queryCRM("SELECT p.charge_date AS finish_date,c.student_id AS student_crm_id, p.amount
            FROM
                payment AS p
                LEFT JOIN contracts AS c ON c.id = p.contract_id 
            WHERE
                c.count_recharge = 0 AND  p.debt=0
                AND p.charge_date >='".$report_week_info->start_date."' AND p.charge_date <='".$report_week_info->end_date."'");
        $this->addItemsTmpStudent($list_collection,$report_week_info->id,3);
        u::query("UPDATE cms_tmp_student AS t LEFT JOIN cms_students AS s ON t.student_crm_id=s.crm_id LEFT JOIN cms_parents AS p ON p.id=s.parent_id SET t.user_id=p.owner_id WHERE t.report_week_id = $report_week_info->id");
      
        u::query("DELETE FROM cms_report_week_sale_hub WHERE report_week_id = $report_week_info->id");
        $list = u::query("SELECT u.id AS user_id,
                    (SELECT count(id) FROM voip24h_data WHERE created_at >='".$report_week_info->start_date." 00:00:00' AND user_id=u.id AND disposition='ANSWERED') AS `call` ,
                    (SELECT SUM(duration) FROM voip24h_data WHERE created_at >='".$report_week_info->start_date." 00:00:00' AND user_id=u.id AND disposition='ANSWERED') AS talk_time,
                    (SELECT count(s.id) FROM cms_students AS s LEFT JOIN cms_parents AS p ON p.id=s.parent_id WHERE s.checkin_at >='".$report_week_info->start_date." 00:00:00' AND p.owner_id=u.id ) AS trial_accept, 
                    (SELECT count(id) FROM cms_tmp_student WHERE type=1 AND user_id=u.id AND report_week_id = $report_week_info->id) AS trial_actual,
                    (SELECT count(id) FROM cms_tmp_student WHERE type=2 AND user_id=u.id AND report_week_id = $report_week_info->id) AS new_enroll,
                    (SELECT SUM(amount) FROM cms_tmp_student WHERE type=3 AND user_id=u.id AND report_week_id = $report_week_info->id) AS collection
                FROM
                    users AS u 
                WHERE
                    u.status =1 ");
        $this->addItemsReportWeekSaleHub($list,$report_week_info->id);

        return "ok";

    }
    public function addItemsTmpStudent($list,$report_week_id,$type) {
        if ($list) {
            $updated_at = date('Y-m-d H:i:s');
            $query = "INSERT INTO cms_tmp_student (report_week_id,student_crm_id, finish_date, type, updated_at, amount) VALUES ";
            if (count($list) > 5000) {
                for($i = 0; $i < 5000; $i++) {
                    $item = $list[$i];
                    $query.= "('$report_week_id', '$item->student_crm_id', '$item->finish_date', '$type', '$updated_at', '$item->amount'),";
                }
                $query = substr($query, 0, -1);
                u::query($query);
                self::addItemsTmpStudent(array_slice($list, 5000),$report_week_id,$type);
            } else {
                foreach($list as $item) {
                    $query.= "('$report_week_id', '$item->student_crm_id', '$item->finish_date', '$type', '$updated_at', '$item->amount'),";
                }
                $query = substr($query, 0, -1);
                u::query($query);
            }
        }
    }
    public function addItemsReportWeekSaleHub($list,$report_week_id) {
        if ($list) {
            $updated_at = date('Y-m-d H:i:s');
            $query = "INSERT INTO cms_report_week_sale_hub (report_week_id,user_id, `call`, talk_time, trial_accept, trial_actual, new_enroll, `collection`, updated_at) VALUES ";
            if (count($list) > 5000) {
                for($i = 0; $i < 5000; $i++) {
                    $item = $list[$i];
                    $query.= "('$report_week_id', '".(int)$item->user_id."', '".(int)$item->call."', '".(int)$item->talk_time."', '".(int)$item->trial_accept."', '".(int)$item->trial_actual."', '".(int)$item->new_enroll."', '".(int)$item->collection."', '$updated_at'),";
                }
                $query = substr($query, 0, -1);
                u::query($query);
                self::addItemsReportWeekSaleHub(array_slice($list, 5000),$report_week_id);
            } else {
                foreach($list as $item) {
                    $query.= "('$report_week_id', '".(int)$item->user_id."', '".(int)$item->call."', '".(int)$item->talk_time."', '".(int)$item->trial_accept."', '".(int)$item->trial_actual."', '".(int)$item->new_enroll."', '".(int)$item->collection."', '$updated_at'),";
                }
                $query = substr($query, 0, -1);
                u::query($query);
            }
        }
    }
}
