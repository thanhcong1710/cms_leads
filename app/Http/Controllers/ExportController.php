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
        if(!$request->user()->hasRole('admin')){
            $cond .= " AND p.owner_id IN (".$request->user_info->users_manager.")";
        }
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
            WHERE $cond ORDER BY p.id DESC LIMIT 100000");
        if(count($list)<10000){
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
        }else{
            self::download_send_headers("data_export_" . date("Y-m-d") . ".csv");
            echo self::array2csv($list);
            die();
        }
    }
    public static function array2csv(array &$array)
    {
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w');
        fputcsv($df, array_keys(reset($array)));
        foreach ($array as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        return ob_get_clean();
    }
    public static function download_send_headers($filename) {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        $expires = gmdate("D, d M Y H:i:s", time()+900);
        header("Expires: {$expires} GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");
    
        // force download  
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
    
        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }
}
