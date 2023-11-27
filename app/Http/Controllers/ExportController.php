<?php

namespace App\Http\Controllers;

use App\Providers\UtilityServiceProvider as u;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as x;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Http\Request;
use App\Models\ProcessExcel;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    public function import(Request $request , $import_id) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Tên phụ huynh');
        $sheet->setCellValue('B1', 'Số điện thoại');
        $sheet->setCellValue('C1', 'Số điện thoại 2');
        $sheet->setCellValue('D1', 'Email');
        $sheet->setCellValue('E1', 'Địa chỉ');
        $sheet->setCellValue('F1', 'Ghi chú');
        $sheet->setCellValue('G1', 'Người phụ trách');
        $sheet->setCellValue('H1', 'Học sinh 1');
        $sheet->setCellValue('I1', 'Ngày sinh học sinh 1');
        $sheet->setCellValue('J1', 'Học sinh 2');
        $sheet->setCellValue('K1', 'Ngày sinh học sinh 2');
        $sheet->setCellValue('L1', 'Trạng thái');
        $sheet->setCellValue('M1', 'Thông tin lỗi');

        $parents = u::query("SELECT * FROM cms_import_parents WHERE import_id=$import_id");
        $arr_status = [
            '0'=>'Chưa xử lý',
            '1'=>'Đã kiểm tra dữ liệu đầu vào',
            '2'=>'Dữ liệu đầu vào không hợp lệ',
            '3'=>'Trùng lặp dữ liệu trong file import',
            '4'=>'Trùng lặp dữ liệu khách hàng đang chăm sóc',
            '6'=> 'Đã import thành công'
        ];
        $sheet->getColumnDimension("A")->setWidth(30);
        $sheet->getColumnDimension("B")->setWidth(30);
        $sheet->getColumnDimension("C")->setWidth(30);
        $sheet->getColumnDimension("D")->setWidth(30);
        $sheet->getColumnDimension("E")->setWidth(30);
        $sheet->getColumnDimension("F")->setWidth(30);
        $sheet->getColumnDimension("G")->setWidth(30);
        $sheet->getColumnDimension("H")->setWidth(30);
        $sheet->getColumnDimension("I")->setWidth(30);
        $sheet->getColumnDimension("J")->setWidth(30);
        $sheet->getColumnDimension("K")->setWidth(30);
        $sheet->getColumnDimension("L")->setWidth(30);
        $sheet->getColumnDimension("M")->setWidth(30);
        for ($i = 0; $i < count($parents) ; $i++) {
            $x = $i + 2;
            $sheet->setCellValue('A' . $x, $parents[$i]->name);
            $sheet->setCellValue('B' . $x, $parents[$i]->gud_mobile1 ? "'".$parents[$i]->gud_mobile1 : $parents[$i]->gud_mobile1);
            $sheet->setCellValue('C' . $x, $parents[$i]->gud_mobile2 ? "'".$parents[$i]->gud_mobile2 : $parents[$i]->gud_mobile2);
            $sheet->setCellValue('D' . $x, $parents[$i]->email);
            $sheet->setCellValue('E' . $x, $parents[$i]->address);
            $sheet->setCellValue('F' . $x, $parents[$i]->note);
            $sheet->setCellValue('G' . $x, $parents[$i]->owner_hrm);
            $sheet->setCellValue('H' . $x, $parents[$i]->student_name_1);
            $sheet->setCellValue('I' . $x, $parents[$i]->student_birthday_1? "'".$parents[$i]->student_birthday_1 : $parents[$i]->student_birthday_1);
            $sheet->setCellValue('J' . $x, $parents[$i]->student_name_2);
            $sheet->setCellValue('K' . $x, $parents[$i]->student_birthday_2 ? "'".$parents[$i]->student_birthday_2 : $parents[$i]->student_birthday_2);
            $sheet->setCellValue('L' . $x, $arr_status[$parents[$i]->status]);
            $sheet->setCellValue('M' . $x, $parents[$i]->error_message);
            $sheet->getRowDimension($x)->setRowHeight(23);

        }
        $writer = new Xlsx($spreadsheet);
        try {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Kết quả import - ID '.$import_id.'.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save("php://output");
        } catch (Exception $exception) {
            throw $exception;
        }
    }
    public function report01(Request $request , $key,$value) {
        set_time_limit(300);
        ini_set('memory_limit', '-1');
        $cond = " 1 ";
        $arr_key =explode(',',$key);
        $arr_value =explode(',',$value);
        foreach($arr_key AS $k=>$key){
            if($key=='keyword'){
                $keyword = $arr_value[$k];
                $cond .= " AND (p.name LIKE '%$keyword%' OR p.mobile_1 LIKE '%$keyword%' OR p.mobile_2 LIKE '%$keyword%')";
            }
            if($key=='start_date'){
                $cond .= " AND p.created_at >= '$arr_value[$k] 00:00:00'";
            }
            if($key=='end_date'){
                $cond .= " AND p.created_at <= '$arr_value[$k] 23:59:59'";
            }
        }
        if(!$request->user()->hasRole('admin') && !$request->user()->hasRole('Supervisor')){
            $cond .= " AND p.owner_id IN (".$request->user_info->users_manager.")";
        }
        $list = u::query("SELECT p.id, p.name AS parent_name,p.status,
                (SELECT name FROM cms_students WHERE parent_id=p.id LIMIT 1) AS student_name,
                (SELECT DATE_FORMAT(birthday,'%Y') FROM cms_students WHERE parent_id=p.id  LIMIT 1) AS student_year,
                (SELECT name FROM cms_sources WHERE id=p.source_id) AS source_name,
                CONCAT(u.name,'-',u.hrm_id) AS owner_name,
                b.name AS branch_name,
                DATE_FORMAT(p.created_at,'%Y-%m-%d') AS created_date,
                (SELECT DATE_FORMAT(care_date,'%Y-%m-%d') FROM cms_customer_care WHERE parent_id =p.id AND status =1 ORDER BY id DESC LIMIT 1) AS last_care_date,
                (SELECT t.name FROM cms_customer_care AS c LEFT JOIN cms_contact_methods AS t ON t.id=c.method_id WHERE c.parent_id =p.id AND c.status =1 ORDER BY c.id DESC LIMIT 1) AS last_method,
                (SELECT count(c.id) FROM cms_customer_care AS c WHERE c.parent_id =p.id AND c.status =1 ) AS total_care
            FROM cms_parents AS p 
                LEFT JOIN users AS u ON p.owner_id = u.id
                LEFT JOIN cms_branches AS b ON b.id = u.branch_id
            WHERE $cond ORDER BY p.id DESC LIMIT 80000");
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Mã KH');
        $sheet->setCellValue('B1', 'Tên KH');
        $sheet->setCellValue('C1', 'Tên HS');
        $sheet->setCellValue('D1', 'Năm sinh');
        $sheet->setCellValue('E1', 'Nguồn');
        $sheet->setCellValue('F1', 'Trạng thái');
        $sheet->setCellValue('G1', 'TVV');
        $sheet->setCellValue('H1', 'Trung tâm');
        $sheet->setCellValue('I1', 'Ngày nhập data');
        $sheet->setCellValue('J1', 'Ngày chăm sóc gần nhất');
        $sheet->setCellValue('K1', 'Hình thức chăm sóc gần nhất');
        $sheet->setCellValue('L1', 'Tổng số lần tương tác');

        $sheet->getColumnDimension("A")->setWidth(20);
        $sheet->getColumnDimension("B")->setWidth(30);
        $sheet->getColumnDimension("C")->setWidth(30);
        $sheet->getColumnDimension("D")->setWidth(10);
        $sheet->getColumnDimension("E")->setWidth(20);
        $sheet->getColumnDimension("F")->setWidth(20);
        $sheet->getColumnDimension("G")->setWidth(30);
        $sheet->getColumnDimension("H")->setWidth(30);
        $sheet->getColumnDimension("I")->setWidth(20);
        $sheet->getColumnDimension("J")->setWidth(20);
        $sheet->getColumnDimension("K")->setWidth(20);
        $sheet->getColumnDimension("L")->setWidth(20);
        for ($i = 0; $i < count($list) ; $i++) {
            $x = $i + 2;
            $sheet->setCellValue('A' . $x, $list[$i]->id);
            $sheet->setCellValue('B' . $x, $list[$i]->parent_name) ;
            $sheet->setCellValue('C' . $x, $list[$i]->student_name );
            $sheet->setCellValue('D' . $x, $list[$i]->student_year);
            $sheet->setCellValue('E' . $x, $list[$i]->source_name);
            $sheet->setCellValue('F' . $x, u::getStatus($list[$i]->status));
            $sheet->setCellValue('G' . $x, $list[$i]->owner_name);
            $sheet->setCellValue('H' . $x, $list[$i]->branch_name);
            $sheet->setCellValue('I' . $x, $list[$i]->created_date);
            $sheet->setCellValue('J' . $x, $list[$i]->last_care_date);
            $sheet->setCellValue('K' . $x, $list[$i]->last_method);
            $sheet->setCellValue('L' . $x, $list[$i]->total_care);
            
            $sheet->getRowDimension($x)->setRowHeight(23);

        }
        $writer = new Xlsx($spreadsheet);
        try {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Báo cáo thông tin.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save("php://output");
        } catch (Exception $exception) {
            throw $exception;
        }
    }
    public function report02(Request $request , $key,$value) {
        set_time_limit(300);
        ini_set('memory_limit', '-1');
        $cond = " 1 ";
        $arr_key =explode(',',$key);
        $arr_value =explode(',',$value);
        $report_week_id = "";
        foreach($arr_key AS $k=>$key){
            if($key=='keyword'){
                $keyword = $arr_value[$k];
                $cond .= " AND (u.name LIKE '%$keyword%' OR u.hrm LIKE '%$keyword%')";
            }
            if($key=='report_week_id'){
                $report_week_id = $arr_value[$k];
            }
        }
        if(!$request->user()->hasRole('admin') && !$request->user()->hasRole('Supervisor')){
            $cond .= " AND u.id IN (".$request->user_info->users_manager.")";
        }

        if(!$report_week_id){
            $report_week_info = u::first("SELECT * FROM cms_report_week WHERE start_date <= CURRENT_DATE AND  end_date>= CURRENT_DATE");
            $report_week_id = $report_week_info->id;
        }
        $cond.=" AND r.report_week_id =  $report_week_id";

        $list = u::query("SELECT CONCAT(u.name,' - ',u.hrm_id) AS user_label,
                t.call AS target_call, t.talk_time AS target_talk_time, t.trial_accept AS target_trial_accept, t.trial_actual AS target_trial_actual, t.new_enroll AS target_new_enroll,
                r.call , r.talk_time , r.trial_accept , r.trial_actual, r.new_enroll, r.collection
            FROM users AS u 
                LEFT JOIN cms_report_week_sale_hub AS r ON r.user_id=u.id
                LEFT JOIN cms_report_target AS t ON t.user_id=u.id AND t.report_week_id=r.report_week_id
            WHERE u.status=1 AND $cond 
            ORDER BY u.id DESC");
        $tmp_report_week = u::first("SELECT CONCAT('Tuần từ ngày ',start_date,' đến ', end_date) AS label FROM cms_report_week WHERE id= $report_week_id");

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Báo cáo tuần Sale HUB -'.$tmp_report_week->label);

        $sheet->setCellValue('A2', 'STT');
        $sheet->setCellValue('B2', 'Tên nhân viên');
        $sheet->setCellValue('C2', 'Target tuần');
        $sheet->setCellValue('H2', 'Thực đạt tuần');
        $sheet->setCellValue('C3', 'Call');
        $sheet->setCellValue('D3', 'Talk time');
        $sheet->setCellValue('E3', 'Trial accept');
        $sheet->setCellValue('F3', 'Trial actual');
        $sheet->setCellValue('G3', 'New Enroll');
        $sheet->setCellValue('H3', 'Call');
        $sheet->setCellValue('I3', 'Talk time');
        $sheet->setCellValue('J3', 'Trial accept');
        $sheet->setCellValue('K3', 'Trial actual');
        $sheet->setCellValue('L3', 'New Enroll');
        $sheet->setCellValue('M3', 'Collection');
        $sheet->mergeCells('A1:M1');
        $sheet->mergeCells('A2:A3');
        $sheet->mergeCells('B2:B3');
        $sheet->mergeCells('C2:G2');
        $sheet->mergeCells('H2:M2');

        ProcessExcel::styleCells($spreadsheet, "A1:M1", NULL, NULL, 16, 1, 3, "center", "center", true, 0, 'Calibri');
        ProcessExcel::styleCells($spreadsheet, "A2:A3", NULL, 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
        ProcessExcel::styleCells($spreadsheet, "B2:B3", NULL, 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
        ProcessExcel::styleCells($spreadsheet, "C2:G2", NULL, 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
        ProcessExcel::styleCells($spreadsheet, "H2:M2", NULL, 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
        ProcessExcel::styleCells($spreadsheet, "C3", NULL, 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
        ProcessExcel::styleCells($spreadsheet, "D3", NULL, 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
        ProcessExcel::styleCells($spreadsheet, "E3", NULL, 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
        ProcessExcel::styleCells($spreadsheet, "F3", NULL, 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
        ProcessExcel::styleCells($spreadsheet, "G3", NULL, 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
        ProcessExcel::styleCells($spreadsheet, "H3", NULL, 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
        ProcessExcel::styleCells($spreadsheet, "I3", NULL, 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
        ProcessExcel::styleCells($spreadsheet, "J3", NULL, 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
        ProcessExcel::styleCells($spreadsheet, "K3", NULL, 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
        ProcessExcel::styleCells($spreadsheet, "L3", NULL, 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
        ProcessExcel::styleCells($spreadsheet, "M3", NULL, 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(2)->setRowHeight(23);
        $sheet->getRowDimension(3)->setRowHeight(23);
        $sheet->getColumnDimension("A")->setWidth(10);
        $sheet->getColumnDimension("B")->setWidth(30);
        $sheet->getColumnDimension("C")->setWidth(20);
        $sheet->getColumnDimension("D")->setWidth(20);
        $sheet->getColumnDimension("E")->setWidth(20);
        $sheet->getColumnDimension("F")->setWidth(20);
        $sheet->getColumnDimension("G")->setWidth(20);
        $sheet->getColumnDimension("H")->setWidth(20);
        $sheet->getColumnDimension("I")->setWidth(20);
        $sheet->getColumnDimension("J")->setWidth(20);
        $sheet->getColumnDimension("K")->setWidth(20);
        $sheet->getColumnDimension("L")->setWidth(20);
        $sheet->getColumnDimension("M")->setWidth(20);
        for ($i = 0; $i < count($list) ; $i++) {
            $x = $i + 4;
            $sheet->setCellValue('A' . $x, $i+1);
            $sheet->setCellValue('B' . $x, $list[$i]->user_label) ;
            $sheet->setCellValue('C' . $x, $list[$i]->target_call );
            $sheet->setCellValue('D' . $x, $list[$i]->target_talk_time);
            $sheet->setCellValue('E' . $x, $list[$i]->target_trial_accept);
            $sheet->setCellValue('F' . $x, $list[$i]->target_trial_actual);
            $sheet->setCellValue('G' . $x, $list[$i]->target_new_enroll);
            $sheet->setCellValue('H' . $x, $list[$i]->call);
            $sheet->setCellValue('I' . $x, $list[$i]->talk_time);
            $sheet->setCellValue('J' . $x, $list[$i]->trial_accept);
            $sheet->setCellValue('K' . $x, $list[$i]->trial_actual);
            $sheet->setCellValue('L' . $x, $list[$i]->new_enroll);
            $sheet->setCellValue('M' . $x, $list[$i]->collection);
            
            $sheet->getRowDimension($x)->setRowHeight(23);
            ProcessExcel::styleCells($spreadsheet, "A$x", 'FFFFFF', '111111', 11, 0, 3, "right", "center", true, 0, 'Cambria');
            ProcessExcel::styleCells($spreadsheet, "B$x", 'FFFFFF', '111111', 11, 0, 3, "left", "center", true, 0, 'Cambria');
            ProcessExcel::styleCells($spreadsheet, "C$x", 'FFFFFF', '111111', 11, 0, 3, "right", "center", true, 0, 'Cambria');
            ProcessExcel::styleCells($spreadsheet, "D$x", 'FFFFFF', '111111', 11, 0, 3, "right", "center", true, 0, 'Cambria');
            ProcessExcel::styleCells($spreadsheet, "E$x", 'FFFFFF', '111111', 11, 0, 3, "right", "center", true, 0, 'Cambria');
            ProcessExcel::styleCells($spreadsheet, "F$x", 'FFFFFF', '111111', 11, 0, 3, "right", "center", true, 0, 'Cambria');
            ProcessExcel::styleCells($spreadsheet, "G$x", 'FFFFFF', '111111', 11, 0, 3, "right", "center", true, 0, 'Cambria');
            ProcessExcel::styleCells($spreadsheet, "H$x", 'FFFFFF', '111111', 11, 0, 3, "right", "center", true, 0, 'Cambria');
            ProcessExcel::styleCells($spreadsheet, "I$x", 'FFFFFF', '111111', 11, 0, 3, "right", "center", true, 0, 'Cambria');
            ProcessExcel::styleCells($spreadsheet, "J$x", 'FFFFFF', '111111', 11, 0, 3, "right", "center", true, 0, 'Cambria');
            ProcessExcel::styleCells($spreadsheet, "K$x", 'FFFFFF', '111111', 11, 0, 3, "right", "center", true, 0, 'Cambria');
            ProcessExcel::styleCells($spreadsheet, "L$x", 'FFFFFF', '111111', 11, 0, 3, "right", "center", true, 0, 'Cambria');
            ProcessExcel::styleCells($spreadsheet, "M$x", 'FFFFFF', '111111', 11, 0, 3, "right", "center", true, 0, 'Cambria');

        }
        $writer = new Xlsx($spreadsheet);
        try {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Báo cáo tuần sale hub.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save("php://output");
        } catch (Exception $exception) {
            throw $exception;
        }
    }
    
    public function report04(Request $request , $key,$value) {
        set_time_limit(300);
        ini_set('memory_limit', '-1');
        $cond = "1";
        $arr_key =explode(',',$key);
        $arr_value =explode(',',$value);
        foreach($arr_key AS $k=>$key){
            if($key=='keyword'){
                $cond.= " AND (p.name LIKE '%".$arr_value[$k]."%' OR p.mobile_1 LIKE '%".$arr_value[$k]."%')";
            }
            if ($key=='branch_id') {
                $cond.= " AND u.branch_id = ".$arr_value[$k];
            }
            if($key=='owner_id'){
                $cond.= " AND  u.id IN (".str_replace("-",",", $arr_value[$k]).")" ;
            }
            if($key=='source_id'){
                $cond .= " AND  p.source_id IN (".str_replace("-",",", $arr_value[$k]).")" ;
            }
            if($key=='source_detail_id'){
                $cond .= " AND  p.source_detail_id IN (".str_replace("-",",", $arr_value[$k]).")" ;
            }
            if ($key=='call_status') {
                $cond.=" AND c.call_status=".$arr_value[$k];
            }
            if ($key=='call_status_sub') {
                $cond.=" AND c.call_status_sub=".$arr_value[$k];
            }
            if($key=='start_date'){
                $cond .= " AND c.created_at >= '".date('Y-m-d 00:00:00',strtotime($arr_value[$k]))."'";
            }
            if($key=='end_date'){
                $cond .= " AND c.created_at <= '".date('Y-m-d 23:59:59',strtotime($arr_value[$k]))."'";
            }
            if($key=='start_date_care'){
                $cond .= " AND c.next_care_date >= '".date('Y-m-d 00:00:00',strtotime($arr_value[$k]))."'";
            }
            if($key=='end_date_care'){
                $cond .= " AND c.next_care_date <= '".date('Y-m-d 23:59:59',strtotime($arr_value[$k]))."'";
            }
        }
        
        if(!$request->user()->hasRole('admin') && !$request->user()->hasRole('Supervisor')){
            $cond .= " AND u.id IN (".$request->user_info->users_manager.")";
        }
        $list = u::query("SELECT p.id AS parent_id, p.name, p.mobile_1, c.call_status, c.call_status_sub, c.next_care_date,
            u.branch_name, CONCAT(u.name, ' - ', u.hrm_id) AS sale_name, c.created_at,
                s.name AS source_name, sd.name AS source_detail_name, c.note    
            FROM cms_customer_care AS c
                LEFT JOIN cms_parents AS p ON c.parent_id=p.id
                LEFT JOIN users AS u ON u.id =c.creator_id 
                LEFT JOIN cms_sources AS s ON s.id=p.source_id
                LEFT JOIN cms_source_detail AS sd ON sd.id=p.source_detail_id 
            WHERE c.status=1 AND c.method_id = 1 AND $cond 
            ORDER BY c.id DESC ");
            
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'STT');
        $sheet->setCellValue('B1', 'Tên khách hàng');
        $sheet->setCellValue('C1', 'SĐT');
        $sheet->setCellValue('D1', 'Trạng thái cuộc gọi');
        $sheet->setCellValue('E1', 'Trạng thái cuộc gọi chi tiết');
        $sheet->setCellValue('F1', 'Ngày hẹn chăm sóc');
        $sheet->setCellValue('G1', 'Trung tâm');
        $sheet->setCellValue('H1', 'Sale');
        $sheet->setCellValue('I1', 'Ngày cập nhật');
        $sheet->setCellValue('J1', 'Nguồn');
        $sheet->setCellValue('K1', 'Nguồn chi tiết');
        $sheet->setCellValue('L1', 'Ghi chú');

        $sheet->getColumnDimension("A")->setWidth(5);
        $sheet->getColumnDimension("B")->setWidth(20);
        $sheet->getColumnDimension("C")->setWidth(20);
        $sheet->getColumnDimension("D")->setWidth(20);
        $sheet->getColumnDimension("E")->setWidth(20);
        $sheet->getColumnDimension("F")->setWidth(20);
        $sheet->getColumnDimension("G")->setWidth(20);
        $sheet->getColumnDimension("H")->setWidth(20);
        $sheet->getColumnDimension("I")->setWidth(20);
        $sheet->getColumnDimension("J")->setWidth(20);
        $sheet->getColumnDimension("K")->setWidth(20);
        $sheet->getColumnDimension("L")->setWidth(20);
        for ($i = 0; $i < count($list) ; $i++) {
            $x = $i + 2;
            $sheet->setCellValue('A' . $x, $i+1);
            $sheet->setCellValue('B' . $x, $list[$i]->name);
            $sheet->setCellValue('C' . $x, $list[$i]->mobile_1) ;
            $sheet->setCellValue('D' . $x, u::genTitleCallStatus($list[$i]->call_status) );
            $sheet->setCellValue('E' . $x, u::genTitleCallStatusSub($list[$i]->call_status_sub));
            $sheet->setCellValue('F' . $x, $list[$i]->next_care_date);
            $sheet->setCellValue('G' . $x, $list[$i]->branch_name);
            $sheet->setCellValue('H' . $x, $list[$i]->sale_name);
            $sheet->setCellValue('I' . $x, $list[$i]->created_at);
            $sheet->setCellValue('J' . $x, $list[$i]->source_name);
            $sheet->setCellValue('K' . $x, $list[$i]->source_detail_name);
            $sheet->setCellValue('L' . $x, $list[$i]->note);
            
            $sheet->getRowDimension($x)->setRowHeight(23);

        }
        $writer = new Xlsx($spreadsheet);
        try {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Báo cáo chi tiết cuộc gọi.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save("php://output");
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    public function report03(Request $request , $key,$value) {
        set_time_limit(300);
        ini_set('memory_limit', '-1');
        $arr_key =explode(',',$key);
        $arr_value =explode(',',$value);
        $cond = "";
        $cond1 = "";
        $cond2 = "";

        foreach($arr_key AS $k=>$key){
            if($key=='start_date'){
                $cond .= " AND p.last_assign_date >= '".date('Y-m-d 00:00:00',strtotime($arr_value[$k]))."'";
                $cond1 .= " AND c.created_at >= '".date('Y-m-d 00:00:00',strtotime($arr_value[$k]))."'";
            }
            if($key=='to_date'){
                $cond .= " AND p.last_assign_date <= '".date('Y-m-d 00:00:00',strtotime($arr_value[$k]))."'";
                $cond1 .= " AND c.created_at <= '".date('Y-m-d 00:00:00',strtotime($arr_value[$k]))."'";
            }
            if($key=='source_id'){
                $cond .= " AND  p.source_id IN (".str_replace("-",",", $arr_value[$k]).")" ;
                $cond1 .= " AND  p.source_id IN (".str_replace("-",",", $arr_value[$k]).")" ;
            }
            if($key=='source_detail_id'){
                $cond .= " AND  p.source_detail_id IN (".str_replace("-",",", $arr_value[$k]).")" ;
                $cond1 .= " AND  p.source_detail_id IN (".str_replace("-",",", $arr_value[$k]).")" ;
            }
            if ($key=='branch_id') {
                $cond2 .= " AND u.branch_id = ".$arr_value[$k];
            }
            if($key=='owner_id'){
                $cond2 .= " AND  u.id IN (".str_replace("-",",", $arr_value[$k]).")" ;
            }
        }
       
        if(!$request->user()->hasRole('admin') && !$request->user()->hasRole('Supervisor')){
            $cond2 .= " AND u.id IN (".$request->user_info->users_manager.")";
        }
        $list = u::query("SELECT u.branch_name,u.name,u.hrm_id, u.id
                FROM users AS u WHERE u.branch_id !=0 $cond2 AND u.status=1 ORDER BY u.branch_id ");
            
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Trung tâm');
        $sheet->setCellValue('B1', 'Tele Sale');
        $sheet->setCellValue('C1', 'Tổng số data đã chia chưa xử lý');
        $sheet->setCellValue('D1', 'Tổng số data kết nối được');
        $sheet->setCellValue('E1', 'Tổng số data không kết nối được');
        $sheet->setCellValue('F1', '0. Blank');
        $sheet->setCellValue('G1', '1. Thuê bao - Tắt máy - Sai số');
        $sheet->setCellValue('H1', '2. Location');
        $sheet->setCellValue('I1', '3. Máy bận - Không nghe máy');
        $sheet->setCellValue('J1', '4. KH hẹn gọi lại sau');
        $sheet->setCellValue('K1', '5. KH Từ chối nói chuyện');
        $sheet->setCellValue('L1', '5.1. KH đã từng sử dụng dịch vụ');
        $sheet->setCellValue('M1', '5.2. KH không quan tâm');
        $sheet->setCellValue('N1', '5.3 KH thực sự không muốn nói chuyện');
        $sheet->setCellValue('O1', '6. KH không phù hợp');
        $sheet->setCellValue('P1', '6.1. Không có con');
        $sheet->setCellValue('Q1', '6.2. Lý do khác');
        $sheet->setCellValue('R1', '7. KH tiềm năng');
        $sheet->setCellValue('S1', '7.1. KH đang cân nhắc');
        $sheet->setCellValue('T1', '7.2. KH hẹn thời gian khác');
        $sheet->setCellValue('U1', '7.3. KH ko muốn làm phiền');
        $sheet->setCellValue('V1', '7.4. Confirm 1');

        $sheet->getColumnDimension("A")->setWidth(30);
        $sheet->getColumnDimension("B")->setWidth(30);
        $sheet->getColumnDimension("C")->setWidth(20);
        $sheet->getColumnDimension("D")->setWidth(20);
        $sheet->getColumnDimension("E")->setWidth(20);
        $sheet->getColumnDimension("F")->setWidth(20);
        $sheet->getColumnDimension("G")->setWidth(20);
        $sheet->getColumnDimension("H")->setWidth(20);
        $sheet->getColumnDimension("I")->setWidth(20);
        $sheet->getColumnDimension("J")->setWidth(20);
        $sheet->getColumnDimension("K")->setWidth(20);
        $sheet->getColumnDimension("L")->setWidth(20);
        $sheet->getColumnDimension("M")->setWidth(20);
        $sheet->getColumnDimension("N")->setWidth(20);
        $sheet->getColumnDimension("O")->setWidth(20);
        $sheet->getColumnDimension("P")->setWidth(20);
        $sheet->getColumnDimension("Q")->setWidth(20);
        $sheet->getColumnDimension("R")->setWidth(20);
        $sheet->getColumnDimension("S")->setWidth(20);
        $sheet->getColumnDimension("T")->setWidth(20);
        $sheet->getColumnDimension("U")->setWidth(20);
        $sheet->getColumnDimension("V")->setWidth(20);
        for ($i = 0; $i < count($list) ; $i++) {
            $data_report = u::first("SELECT (SELECT COUNT(p.id) FROM cms_parents AS p WHERE p.owner_id=". $list[$i]->id." AND p.status=0 AND (p.last_care_date ='' OR  p.last_care_date IS NULL) $cond ) AS total_new,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id WHERE c.creator_id = ". $list[$i]->id." AND c.call_status IN (4,5,6,7) $cond) AS total_connect,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id WHERE c.creator_id = ". $list[$i]->id." AND c.call_status IN (1,2,3) $cond) AS total_not_connect,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id WHERE c.creator_id = ". $list[$i]->id." AND c.call_status = 0 $cond) AS detail_0,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id WHERE c.creator_id = ". $list[$i]->id." AND c.call_status = 1 $cond) AS detail_1,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id WHERE c.creator_id = ". $list[$i]->id." AND c.call_status = 2 $cond) AS detail_2,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id WHERE c.creator_id = ". $list[$i]->id." AND c.call_status = 3 $cond) AS detail_3,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id WHERE c.creator_id = ". $list[$i]->id." AND c.call_status = 4 $cond) AS detail_4,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id WHERE c.creator_id = ". $list[$i]->id." AND c.call_status = 5 $cond) AS detail_5,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id WHERE c.creator_id = ". $list[$i]->id." AND c.call_status = 6 $cond) AS detail_6,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id WHERE c.creator_id = ". $list[$i]->id." AND c.call_status = 7 $cond) AS detail_7,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id WHERE c.creator_id = ". $list[$i]->id." AND c.call_status = 5 AND c.call_status_sub = 51 $cond) AS detail_51,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id WHERE c.creator_id = ". $list[$i]->id." AND c.call_status = 5 AND c.call_status_sub = 52 $cond) AS detail_52,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id WHERE c.creator_id = ". $list[$i]->id." AND c.call_status = 5 AND c.call_status_sub = 53 $cond) AS detail_53,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id WHERE c.creator_id = ". $list[$i]->id." AND c.call_status = 6 AND c.call_status_sub = 61 $cond) AS detail_61,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id WHERE c.creator_id = ". $list[$i]->id." AND c.call_status = 6 AND c.call_status_sub = 62 $cond) AS detail_62,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id WHERE c.creator_id = ". $list[$i]->id." AND c.call_status = 7 AND c.call_status_sub = 71 $cond) AS detail_71,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id WHERE c.creator_id = ". $list[$i]->id." AND c.call_status = 7 AND c.call_status_sub = 72 $cond) AS detail_72,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id WHERE c.creator_id = ". $list[$i]->id." AND c.call_status = 7 AND c.call_status_sub = 73 $cond) AS detail_73,
                (SELECT COUNT(c.id) FROM cms_customer_care AS c LEFT JOIN cms_parents AS p ON p.id=c.parent_id WHERE c.creator_id = ". $list[$i]->id." AND c.call_status = 7 AND c.call_status_sub = 74 $cond) AS detail_74");
            $x = $i + 2;
            $sheet->setCellValue('A' . $x, $list[$i]->branch_name);
            $sheet->setCellValue('B' . $x, $list[$i]->name." - ".$list[$i]->hrm_id);
            $sheet->setCellValue('C' . $x, $data_report->total_new) ;
            $sheet->setCellValue('D' . $x, $data_report->total_not_connect );
            $sheet->setCellValue('E' . $x, $data_report->total_connect);
            $sheet->setCellValue('F' . $x, $data_report->detail_0);
            $sheet->setCellValue('G' . $x, $data_report->detail_1);
            $sheet->setCellValue('H' . $x, $data_report->detail_2);
            $sheet->setCellValue('I' . $x, $data_report->detail_3);
            $sheet->setCellValue('J' . $x, $data_report->detail_4);
            $sheet->setCellValue('K' . $x, $data_report->detail_5);
            $sheet->setCellValue('L' . $x, $data_report->detail_51);
            $sheet->setCellValue('M' . $x, $data_report->detail_52);
            $sheet->setCellValue('N' . $x, $data_report->detail_53);
            $sheet->setCellValue('O' . $x, $data_report->detail_6);
            $sheet->setCellValue('P' . $x, $data_report->detail_61);
            $sheet->setCellValue('Q' . $x, $data_report->detail_62);
            $sheet->setCellValue('R' . $x, $data_report->detail_7);
            $sheet->setCellValue('S' . $x, $data_report->detail_71);
            $sheet->setCellValue('T' . $x, $data_report->detail_72);
            $sheet->setCellValue('U' . $x, $data_report->detail_73);
            $sheet->setCellValue('V' . $x, $data_report->detail_74);
            $sheet->getRowDimension($x)->setRowHeight(23);
        }
        $writer = new Xlsx($spreadsheet);
        try {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="Báo cáo cuộc gọi.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save("php://output");
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
