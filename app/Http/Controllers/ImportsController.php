<?php

namespace App\Http\Controllers;

use App\Providers\UtilityServiceProvider as u;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as x;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImportsController extends Controller
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
        
        $pagination = (object)$request->pagination;
        $page = isset($pagination->cpage) ? (int) $pagination->cpage : 1;
        $limit = isset($pagination->limit) ? (int) $pagination->limit : 20;
        $offset = $page == 1 ? 0 : $limit * ($page-1);
        $limitation =  $limit > 0 ? " LIMIT $offset, $limit": "";
        $cond = " 1 ";
        $total = u::first("SELECT count(id) AS total FROM cms_imports AS i WHERE $cond ");
        $list = u::query("SELECT i.*, (SELECT name FROM users WHERE id=i.creator_id) AS creator_name 
            FROM cms_imports AS i WHERE $cond ORDER BY i.id DESC $limitation");
        $data = u::makingPagination($list, $total->total, $page, $limit);
        return response()->json($data);
    }
    public function upload(Request $request){
        $dataRequest = $request->all();
        $data_mes = (object) [];

        $attachedFile = $dataRequest['files'];
        if (!$attachedFile) {
            $data_mes->error = true;
            $data_mes->message = "File upload không hợp lệ";
        }

        // SAVE FILES TO SERVER
        $explod = explode(',', $attachedFile);
        $decod = base64_decode($explod[1]);
        if (str_contains($explod[0], 'spreadsheetml')) {
            $extend = 'xlsx';
        } else {
            $data_mes->error = true;
            $data_mes->message = "File upload không hợp lệ";
        }
        $fileAttached = md5($request->attached_file.date('YmdHis')).'.'.$extend;
        $p = __DIR__.'/../../../public/static/upload/'.$fileAttached;
        file_put_contents($p, $decod);
        $reader = new x();
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($p);
        $sheet = $spreadsheet->setActiveSheetIndex(0);

        $dataXslx = $sheet->toArray();
        unset($dataXslx[0]);
        $import_id = u::insertSimpleRow(array(
            'file_name'=>$fileAttached,
            'file_link'=>'static/upload/'.$fileAttached,
            'status'=>0,
            'created_at'=> date('Y-m-d H:i:s'),
            'creator_id'=>(int)$request->user()->id,
        ),'cms_imports');
        $this->addItemDataImport($dataXslx,$import_id,(int)$request->user()->id);
        // self::processCheckDuplicateData();
        // self::processInsertData();
        
        $data_mes->message = 'Import file thành công!';
        $data_mes->error = false;
        return response()->json($data_mes);
       
    }
    public function addItemDataImport($list,$import_id,$creator_id) {
        if ($list) {
            $created_at = date('Y-m-d H:i:s');
            $query = "INSERT INTO cms_import_parents (import_id,`name`,email,gud_mobile1,note,created_at,creator_id,`status`,error_message) VALUES ";
            if (count($list) > 10000) {
                for($i = 1; $i < 10000; $i++) {
                    $item = $this->convertData($list[$i]);
                    $validate = $this->validateData($item);
                    $status = $validate->has_error ? 2 : 0;
                    $error_message = $validate->message;
                    $gud_mobile1 = $item->gud_mobile1 ? $item->gud_mobile1 : $list[$i]->gud_mobile1;
                    $query.= "('$import_id','$item->name','$item->email','$gud_mobile1','$item->noe','$created_at','$creator_id',$status,$error_message),";
                    
                }
                $query = substr($query, 0, -1);
                u::query($query);
                $this->addItemDataImport(array_slice($list, 10000),$import_id,$creator_id);
            } else {
                foreach($list as $i=>$item) {
                    $item = $this->convertData($item);
                    $validate = $this->validateData($item);
                    $status = $validate->has_error ? 2 : 0;
                    $error_message = $validate->message;
                    $gud_mobile1 = $item->gud_mobile1 ? $item->gud_mobile1 : $list[$i]->gud_mobile1;
                    $query.= "('$import_id','$item->name','$item->email','$gud_mobile1','$item->noe','$created_at','$creator_id',$status,$error_message),";
                }
                $query = substr($query, 0, -1);
                u::query($query);
            }
        }
    }
    public function convertData($data){
        $result = (object)array(
            'name'=>$data[0],
            'gud_mobile1'=> u::phoneNew($data[1]),
            'email'=>$data[2],
            'address'=>$data[3],
            'note'=>$data[4],
        );
        return $result;
    }
    public function validateData($data){
        $result = (object)array(
            'has_error'=>0,
            'message'=>""
        );
        if(!$data->name){
            $result->has_error = 1;
            $result->message = "Tên phụ huynh không để trống";
        }if(!$data->gud_mobile1){
            $result->has_error = 1;
            $result->message = "Số điện thoại không hợp lệ";
        }
        return $result;
    }
}
