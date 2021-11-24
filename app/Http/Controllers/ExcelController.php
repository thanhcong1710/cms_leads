<?php

namespace App\Http\Controllers;

use App\Providers\UtilityServiceProvider as u;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\ProcessExcel;

class ExcelController extends Controller
{
    public function dashboard(Request $request) {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->mergeCells('A1:J1');
        $sheet->mergeCells('A2:J2');
        $sheet->mergeCells('A3:J3');
        $sheet->mergeCells('A4:J4');
        $sheet->setCellValue('A1', 'CÔNG TY CỔ PHẦN GIÁO DỤC TƯ DUY & SÁNG TẠO QUỐC TẾ CMS');
        $sheet->setCellValue('A2', 'THÔNG TIN THỐNG KÊ DỮ LIỆU CUỘC GỌI');
        $sheet->getRowDimension('1')->setRowHeight(36);
        $sheet->getRowDimension('2')->setRowHeight(50);
        $sheet->getRowDimension('3')->setRowHeight(33);
        $sheet->getRowDimension('4')->setRowHeight(33);
        $sheet->getRowDimension('5')->setRowHeight(30);

        $sheet->setCellValue('A5', 'STT');
        $sheet->setCellValue('B5', 'Nhân viên');
        $sheet->setCellValue('C5', 'Trung tâm');
        $sheet->setCellValue('D5', 'Tổng số cuộc gọi');
        $sheet->setCellValue('E5', 'Trả lời');
        $sheet->setCellValue('F5', 'Không trả lời');
        $sheet->setCellValue('G5', 'Gọi nhỡ');
        $sheet->setCellValue('H5', 'Gọi vào');
        $sheet->setCellValue('I5', 'Gọi ra');
        
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(40);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);
        
        ProcessExcel::styleCells($spreadsheet, "A1:I1", NULL, NULL, 16, 1, 3, "center", "center", true, 0, 'Calibri');
        ProcessExcel::styleCells($spreadsheet, "A2:I2", NULL, NULL, 20, 1, 3, "center", "center", true, 0, 'Cambria');
        ProcessExcel::styleCells($spreadsheet, "A3:I3", NULL, NULL, 12, 1, 3, "center", "center", true, 0, 'Arial');
        ProcessExcel::styleCells($spreadsheet, "A4:I4", NULL, NULL, 12, 1, 3, "center", "center", true, 0, 'Arial');

        ProcessExcel::styleCells($spreadsheet, "A5", '223b54', 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
        ProcessExcel::styleCells($spreadsheet, "B5", '223b54', 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
        ProcessExcel::styleCells($spreadsheet, "C5", '223b54', 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
        ProcessExcel::styleCells($spreadsheet, "D5", '223b54', 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
        ProcessExcel::styleCells($spreadsheet, "E5", '223b54', 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
        ProcessExcel::styleCells($spreadsheet, "F5", '223b54', 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
        ProcessExcel::styleCells($spreadsheet, "G5", '223b54', 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
        ProcessExcel::styleCells($spreadsheet, "H5", '223b54', 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
        ProcessExcel::styleCells($spreadsheet, "I5", '223b54', 'FFFFFF', 12, 1, 3, "center", "center", true, 0, 'Cambria');
    
        $owner_id = isset($request->owner_id) ? $request->owner_id : '';
        $end_date = isset($request->end_date) ? $request->end_date : '';
        $start_date = isset($request->start_date) ? $request->start_date : '';
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

        $sql_tongcuocgoi = " (SELECT count(id) FROM voip24h_data WHERE user_id=u.id $cond_sub)";
        $sql_traloi = "(SELECT count(id) FROM voip24h_data WHERE user_id=u.id $cond_sub AND (disposition = 'NO ANSWER' OR disposition = 'BUSY'))";
        $sql_khongtraloi = "(SELECT count(id) FROM voip24h_data WHERE user_id=u.id $cond_sub AND (disposition = 'NO ANSWER' OR disposition = 'BUSY') AND type='outbound')";
        $sql_goinho = "(SELECT count(id) FROM voip24h_data WHERE user_id=u.id $cond_sub AND (disposition = 'NO ANSWER' OR disposition = 'BUSY') AND type='inbound')";
        $sql_vao = "(SELECT count(id) FROM voip24h_data WHERE user_id=u.id $cond_sub AND type='inbound')";
        $sql_ra = "(SELECT count(id) FROM voip24h_data WHERE user_id=u.id $cond_sub AND type='outbound')";
        $list = u::query("SELECT u.id, u.name, u.hrm_id, u.branch_name ,
                $sql_tongcuocgoi AS tongcuocgoi,
                $sql_traloi AS traloi,
                $sql_khongtraloi AS khongtraloi,
                $sql_goinho AS goinho,
                $sql_vao AS vao,
                $sql_ra AS ra
            FROM users AS u WHERE $cond AND u.sip_id IS NOT NULL AND u.sip_id!=0 ORDER BY u.id DESC ");
        
        for ($i = 0; $i < count($list) ; $i++) {
            $x = $i + 6;
            $sheet->setCellValue('A' . $x, $i + 1);
            $sheet->setCellValue('B' . $x, $list[$i]->name." - ".$list[$i]->hrm_id);
            $sheet->setCellValue('C' . $x, $list[$i]->branch_name);
            $sheet->setCellValue('D' . $x, $list[$i]->tongcuocgoi);
            $sheet->setCellValue('E' . $x, $list[$i]->traloi);
            $sheet->setCellValue('F' . $x, $list[$i]->khongtraloi);
            $sheet->setCellValue('G' . $x, $list[$i]->goinho);
            $sheet->setCellValue('H' . $x, $list[$i]->vao);
            $sheet->setCellValue('I' . $x, $list[$i]->ra);
            $sheet->getRowDimension($x)->setRowHeight(23);
        }
        $writer = new Xlsx($spreadsheet);
        try {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="THÔNG TIN THỐNG KÊ DỮ LIỆU CUỘC GỌI.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save("php://output");
        } catch (Exception $exception) {
            throw $exception;
        }
        exit;
    }
}
