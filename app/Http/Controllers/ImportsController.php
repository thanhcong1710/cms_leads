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
        // $keyword = isset($request->keyword) ? $request->keyword : '';
        
        $pagination = (object)$request->pagination;
        $page = isset($pagination->cpage) ? (int) $pagination->cpage : 1;
        $limit = isset($pagination->limit) ? (int) $pagination->limit : 20;
        $offset = $page == 1 ? 0 : $limit * ($page-1);
        $limitation =  $limit > 0 ? " LIMIT $offset, $limit": "";
        $cond = " 1 ";
        if($status!==''){
            $cond .= " AND status=$status";
        }
        if(!$request->user()->hasRole('admin')){
            $cond .= " AND i.creator_id IN (".$request->user_info->users_manager.")";
        }
        $total = u::first("SELECT count(id) AS total FROM cms_imports AS i WHERE $cond ");
        $list = u::query("SELECT i.*, (SELECT name FROM users WHERE id=i.creator_id) AS creator_name ,
                (SELECT count(id) FROM cms_import_parents WHERE import_id=i.id AND status=6) AS count_success,
                (SELECT count(id) FROM cms_import_parents WHERE import_id=i.id AND status!=6) AS count_error
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
        $this->processCheckDuplicateData($import_id);
        u::query("UPDATE cms_import_parents AS p LEFT JOIN users AS u ON u.hrm_id=p.owner_hrm SET p.owner_id=u.id WHERE p.import_id =$import_id");
        $data = u::query("SELECT * FROM cms_import_parents WHERE import_id =$import_id");
        $total_error = u::first("SELECT count(id) AS total FROM cms_import_parents WHERE import_id =$import_id AND status IN (2,3,4)");
        $total_validate = u::first("SELECT count(id) AS total FROM cms_import_parents WHERE import_id =$import_id AND status IN (1)");
        
        $data_mes->data = $data;
        $data_mes->total_error = $total_error->total;
        $data_mes->total_validate = $total_validate->total;
        $data_mes->message = 'Import file thành công!';
        $data_mes->import_id = $import_id;
        $data_mes->error = false;
        return response()->json($data_mes);
       
    }
    public function addItemDataImport($list,$import_id,$creator_id) {
        if ($list) {
            $created_at = date('Y-m-d H:i:s');
            $query = "INSERT INTO cms_import_parents (import_id,`name`,email,gud_mobile1,`address`,note,created_at,creator_id,`status`,error_message,student_name_1,student_name_2,student_birthday_1,student_birthday_2,owner_hrm) VALUES ";
            if (count($list) > 10000) {
                for($i = 0; $i < 10000; $i++) {
                    $item = $this->convertData($list[$i]);
                    $validate = $this->validateData($item);
                    $status = $validate->has_error ? 2 : 1;
                    $error_message = $validate->message;
                    $gud_mobile1 = $item->gud_mobile1 ? $item->gud_mobile1 : $list[$i][1];
                    $query.= "('$import_id','$item->name','$item->email','$gud_mobile1','$item->address','$item->note','$created_at','$creator_id',$status,'$error_message','$item->student_name_1','$item->student_name_2',".($item->student_birthday_1 ? "'$item->student_birthday_1'":"NULL").",".($item->student_birthday_2 ? "'$item->student_birthday_2'":"NULL").",'$item->owner_hrm'),";
                    
                }
                $query = substr($query, 0, -1);
                u::query($query);
                $this->addItemDataImport(array_slice($list, 10000),$import_id,$creator_id);
            } else {
                foreach($list as $i=>$item) {
                    $item = $this->convertData($item);
                    $validate = $this->validateData($item);
                    $status = $validate->has_error ? 2 : 1;
                    $error_message = $validate->message;
                    $gud_mobile1 = $item->gud_mobile1 ? $item->gud_mobile1 : $list[$i][1];
                    $query.= "('$import_id','$item->name','$item->email','$gud_mobile1','$item->address','$item->note','$created_at','$creator_id',$status,'$error_message','$item->student_name_1','$item->student_name_2',".($item->student_birthday_1 ? "'$item->student_birthday_1'":"NULL").",".($item->student_birthday_2 ? "'$item->student_birthday_2'":"NULL").",'$item->owner_hrm'),";
                }
                $query = substr($query, 0, -1);
                u::query($query);
            }
        }
    }
    public function convertData($data){
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$data[7])) {
            $student_birthday_1 = $data[7];
        } else {
            $student_birthday_1 = NULL;
        }
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$data[9])) {
            $student_birthday_2 = $data[9];
        } else {
            $student_birthday_2 = NULL;
        }
        $result = (object)array(
            'name'=>$data[0],
            'gud_mobile1'=> u::phoneNew($data[1]),
            'email'=>$data[2],
            'address'=>$data[3],
            'note'=>$data[4],
            'owner_hrm'=>$data[5],
            'student_name_1'=>$data[6],
            'student_birthday_1'=>$student_birthday_1,
            'student_name_2'=>$data[8],
            'student_birthday_2'=>$student_birthday_2,
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
    public function processCheckDuplicateData($import_id){
        // check duplicate cms_import_parents
        $list = u::query("SELECT p.id FROM cms_import_parents AS p 
            WHERE p.import_id = $import_id AND 
                (SELECT count(id) FROM cms_import_parents  WHERE import_id = $import_id AND gud_mobile1 = p.gud_mobile1 AND id<p.id)>0");
        if(!empty($list)){
            $sql_update = "INSERT INTO cms_import_parents (id,`status`,error_message) VALUES ";
            foreach($list AS $row){
                $sql_update.="($row->id,3,'Trùng lặp dữ liệu trong file import'),";
            }
            $sql_update = substr($sql_update, 0, -1);
            $sql_update.=" ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `status` = VALUES(`status`), `error_message` = VALUES(`error_message`)";
            u::query($sql_update);
        }
        // check duplicate cms_parents
        $list = u::query("SELECT p.id, u.name, u.hrm_id,u.branch_name FROM cms_import_parents AS p 
                LEFT JOIN cms_parents AS ps ON (ps.mobile_1=p.gud_mobile1 OR ps.mobile_2=p.gud_mobile1)
                LEFT JOIN users AS u ON ps.owner_id = u.id
            WHERE p.import_id = $import_id AND ps.id IS NOT NULL");
        if(!empty($list)){
            $sql_update = "INSERT INTO cms_import_parents (id,`status`,error_message) VALUES ";
            foreach($list AS $row){
                $sql_update.="($row->id,4,'SĐT đang thuộc quyền quản lý của nhân viên $row->name - $row->hrm_id $row->branch_name'),";
            }
            $sql_update = substr($sql_update, 0, -1);
            $sql_update.=" ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `status` = VALUES(`status`), `error_message` = VALUES(`error_message`)";
            u::query($sql_update);
        }
    }
    public function assign(Request $request){
        $source_id = $request->source_id;
        $arr_owner = $request->owners_id;
        $import_id = $request->import_id;
        $list_data = u::query("SELECT * FROM cms_import_parents WHERE import_id=$import_id AND status=1");
        $this->addItemDataParent($list_data,$arr_owner,$source_id,$request->user()->id);
        u::query("UPDATE cms_import_parents SET status=6 WHERE import_id=$import_id AND status=1");
        u::query("UPDATE cms_imports SET status=1 WHERE id=$import_id ");
        u::query("UPDATE cms_students AS s LEFT JOIN cms_parents AS p ON s.gud_mobile_1 =p.mobile_1 SET s.parent_id=p.id WHERE s.parent_id IS NULL ");
        $data = u::first("SELECT (SELECT count(id) FROM cms_import_parents WHERE import_id=$import_id AND status=6) AS total_success,
            (SELECT count(id) FROM cms_import_parents WHERE import_id=$import_id AND status!=6) AS total_error");
        return response()->json($data);
    }
    public function addItemDataParent($list,$arr_owner,$source_id,$creator_id) {
        if ($list) {
            $created_at = date('Y-m-d H:i:s');
            $query = "INSERT INTO cms_parents (`name`,email,mobile_1,`address`,note,created_at,creator_id,`status`,source_id,owner_id) VALUES ";
            $query_student = "INSERT INTO cms_students (`name`,`birthday`,created_at,creator_id,gud_mobile_1) VALUES ";
            $check_import_student =0;
            if (count($list) > 10000) {
                for($i = 0; $i < 10000; $i++) {
                    $item = (object)$list[$i];
                    $owner_id = $item->owner_id? $item->owner_id : $arr_owner[$i%count($arr_owner)];
                    $query.= "('$item->name','$item->email','$item->gud_mobile1','$item->address','$item->note','$created_at','$creator_id',1,'$source_id','$owner_id'),";
                    if($item->student_name_1){
                        $check_import_student =1;
                        $student_birthday_1 = $item->student_birthday_1 ? "'".$item->student_birthday_1."'" :NULL;
                        $query_student.= "('$item->student_name_1',$student_birthday_1,'$created_at','$creator_id','$item->gud_mobile1'),";
                    }
                    if($item->student_name_2){
                        $check_import_student =1;
                        $student_birthday_2 = $item->student_birthday_2 ? "'".$item->student_birthday_2."'" :NULL;
                        $query_student.= "('$item->student_name_2',$student_birthday_2,'$created_at','$creator_id','$item->gud_mobile1'),";
                    }
                    
                }
                $query = substr($query, 0, -1);
                u::query($query);
                if($check_import_student){
                    $query_student = substr($query_student, 0, -1);
                    u::query($query_student);
                }
                $this->addItemDataImport(array_slice($list, 10000),$arr_owner,$source_id,$creator_id);
            } else {
                foreach($list as $i=>$item) {
                    $item = (object)$item;
                    $owner_id = $item->owner_id? $item->owner_id : $arr_owner[$i%count($arr_owner)];
                    $query.= "('$item->name','$item->email','$item->gud_mobile1','$item->address','$item->note','$created_at','$creator_id',1,'$source_id','$owner_id'),";
                    if($item->student_name_1){
                        $check_import_student =1;
                        $query_student.= "('$item->student_name_1',".($item->student_birthday_1 ? "'$item->student_birthday_1'":"NULL").",'$created_at','$creator_id','$item->gud_mobile1'),";
                    }
                    if($item->student_name_2){
                        $check_import_student =1;
                        $student_birthday_2 = $item->student_birthday_2 ? "'".$item->student_birthday_2."'" :NULL;
                        $query_student.= "('$item->student_name_2',".($item->student_birthday_2 ? "'$item->student_birthday_2'":"NULL").",'$created_at','$creator_id','$item->gud_mobile1'),";
                    }
                }
                $query = substr($query, 0, -1);
                u::query($query);
                if($check_import_student){
                    $query_student = substr($query_student, 0, -1);
                    u::query($query_student);
                }
            }
        }
    }
}