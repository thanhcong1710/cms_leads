<?php

namespace App\Http\Controllers;

use App\Models\CameraAI;
use App\Providers\UtilityServiceProvider as u;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SocketIO;

class CameraAIController extends Controller
{
    public function __construct()
    {
        $this->baseUri = "103.226.250.52";
    }
    public function ipn(Request $request){
        if($request->data_type == 'log'){
            u::queryCRM("INSERT INTO camera_ai_checkin (aliasID,deviceID,personID,placeID,`date`,detected_image_url,personType,meta_data,created_at) 
                VALUES ('$request->aliasID','$request->deviceID','$request->personID','$request->placeID','$request->date','$request->detected_image_url','$request->personType','".json_encode($request->input())."','".date('Y-m-d H:i:s')."')");
            
            $branch_info = u::firstCRM("SELECT id FROM branches WHERE placeID=".$request->placeID);
            if($request->aliasID){
                $student_info = u::firstCRM("SELECT s.branch_id, s.name, s.crm_id, s.accounting_id, c.avatar_url
                    FROM students AS s LEFT JOIN camera_ai_student AS c ON c.student_id=s.id WHERE s.id='".$request->aliasID."'");
                $data =array(
                    'branch_id' => $branch_info->id,
                    'name' => $student_info->name,
                    'crm_id'=> $student_info->crm_id,
                    'accounting_id'=> $student_info->accounting_id,
                    'avatar_url'=> $student_info->avatar_url
                );
            }else{
                $data =array(
                    'branch_id' => $branch_info->id,
                    'name' => 'Khách vãng lai',
                    'crm_id'=> '',
                    'accounting_id'=> '',
                    'avatar_url'=> ''
                );
            }

            $this->socketIo($branch_info->id,'camera_ai', $data);
        }
        return response()->json("ok");
    }
    /**
     * Display a listing of the resource
     *
     * @return \Illuminate\Http\Response
     */
    public function listAction(Request $request)
    {
        $keyword = isset($request->keyword) ? $request->keyword : '';
        $branch_id = isset($request->branch_id) ? $request->branch_id : [];
        $end_date = isset($request->end_date) ? $request->end_date : '';
        $start_date = isset($request->start_date) ? $request->start_date : '';

        $pagination = (object)$request->pagination;
        $page = isset($pagination->cpage) ? (int) $pagination->cpage : 1;
        $limit = isset($pagination->limit) ? (int) $pagination->limit : 20;
        $offset = $page == 1 ? 0 : $limit * ($page-1);
        $limitation =  $limit > 0 ? " LIMIT $offset, $limit": "";
        $cond = " 1 ";
        
        if ($keyword !== '') {
            $cond .= " AND (s.name LIKE '%$keyword%' OR s.gud_mobile1 LIKE '%$keyword%' OR s.gud_mobile2 LIKE '%$keyword%' OR s.crm_id LIKE '%$keyword%') ";
        }
        if ($end_date !== '') {
            $cond .= " AND c.date < '$end_date 23:59:59' ";
        }
        if ($start_date !== '') {
            $cond .= " AND c.date > '$start_date 00:00:00'";
        }
        if (!empty($branch_id)) {
            $cond .= " AND b.id IN (".implode(",",$branch_id).")";
        }elseif($request->user()->branch_id !=0){
            $cond .= " AND b.id = ".$request->user()->branch_id;
        }
        
        $order_by = " ORDER BY c.id DESC ";
        
        $total = u::firstCRM("SELECT count(c.id) AS total 
            FROM camera_ai_checkin AS c 
                LEFT JOIN students AS s ON s.id= c.aliasID
                LEFT JOIN branches AS b ON b.placeID=c.placeID
            WHERE $cond");
        
        $list = u::queryCRM("SELECT IF(s.id IS NULL, 'Vãng Lai', CONCAT(s.name,' - ',s.crm_id)) AS student_name,
                b.name AS branch_name, c.date, c.detected_image_url
            FROM camera_ai_checkin AS c 
                LEFT JOIN students AS s ON s.id= c.aliasID
                LEFT JOIN branches AS b ON b.placeID=c.placeID
            WHERE $cond $order_by $limitation");
        $data = u::makingPagination($list, $total->total, $page, $limit);
        
        return response()->json($data);
    }
    public function listStudent(Request $request)
    {
        $keyword = isset($request->keyword) ? $request->keyword : '';
        $branch_id = isset($request->branch_id) ? $request->branch_id : [];

        $pagination = (object)$request->pagination;
        $page = isset($pagination->cpage) ? (int) $pagination->cpage : 1;
        $limit = isset($pagination->limit) ? (int) $pagination->limit : 20;
        $offset = $page == 1 ? 0 : $limit * ($page-1);
        $limitation =  $limit > 0 ? " LIMIT $offset, $limit": "";
        $cond = " 1 ";
        
        if ($keyword !== '') {
            $cond .= " AND (s.name LIKE '%$keyword%' OR s.gud_mobile1 LIKE '%$keyword%' OR s.gud_mobile2 LIKE '%$keyword%' OR s.crm_id LIKE '%$keyword%') ";
        }
        
        if (!empty($branch_id)) {
            $cond .= " AND s.branch_id IN (".implode(",",$branch_id).")";
        }elseif($request->user()->branch_id !=0){
            $cond .= " AND s.branch_id = ".$request->user()->branch_id;
        }
        if ($request->status == 1) {
            $cond .= " AND cs.face_id IS NOT NULL ";
        }elseif($request->status == 2){
            $cond .= " AND cs.face_id IS NULL ";
        }
        
        $order_by = " ORDER BY s.id DESC ";
        
        $total = u::firstCRM("SELECT count(s.id) AS total FROM students AS s LEFT JOIN camera_ai_student AS cs ON s.id=cs.student_id WHERE $cond");
        $list = u::queryCRM("SELECT s.name,b.name AS branch_name ,s.crm_id, cs.face_id,s.id AS student_id,cs.avatar_url
            FROM students AS s 
                LEFT JOIN camera_ai_student AS cs ON s.id=cs.student_id
                LEFT JOIN branches AS b ON  b.id=s.branch_id
            WHERE $cond $order_by $limitation");
        $data = u::makingPagination($list, $total->total, $page, $limit);
        
        return response()->json($data);
    }

    public function updateStudent(Request $request){
        $dataRequest = $request->all();
        $data_mes = (object) [];
        $attachedFile = $dataRequest['files'];
        if (!$attachedFile) {
            $data_mes->error = true;
            $data_mes->message = "File upload không hợp lệ";
        }
        $extension = explode('/', mime_content_type($attachedFile))[1];
        list($type, $data) = explode(';', $attachedFile);
        list(, $data)      = explode(',', $data);
        $decod = base64_decode($data);

        $fileAttached = md5($request->student_id.date('YmdHis')).'.'.$extension;
        $p = __DIR__.'/../../../public/static/avatars/'.$fileAttached;
        $avatar_url = '/static/avatars/'.$fileAttached;
        file_put_contents($p, $decod);

        $camera_ai_student_info = u::firstCRM("SELECT * FROM camera_ai_student WHERE student_id = $request->student_id");
        if($camera_ai_student_info){
            $curr_path = __DIR__.'/../../../public'.$camera_ai_student_info->avatar_url;
            if (file_exists($curr_path)) {
                unlink($curr_path);
            }
            u::queryCRM("UPDATE camera_ai_student SET avatar_url='$avatar_url',updated_at='".date('Y-m-d H:i:s')."' WHERE student_id= $request->student_id");
        }else{
            u::queryCRM("INSERT INTO camera_ai_student (student_id,avatar_url,updated_at) VALUES ('$request->student_id','$avatar_url','".date('Y-m-d H:i:s')."')");
        }
        $cameraAI = new CameraAI();
        $cameraAI->registerByUrl($request->student_id);
        return response()->json($data_mes);
    }
    public function pushData(Request $request){
        $cameraAI = new CameraAI();
        $data=$cameraAI->registerByUrl($request->student_id);
        
        return response()->json($data);
    }
    public function pushAllData(Request $request){
        $list_stuent = u::queryCRM("SELECT student_id FROM camera_ai_student WHERE face_id IS NULL");
        $cameraAI = new CameraAI();
        foreach($list_stuent AS $student){
            $cameraAI->registerByUrl($student->student_id);
        } 
        return response()->json("ok");
    }
    public function socketIo($user_id,$event,$data){

        $arr=[
            'user_id'=>$user_id,
            'event'=>$event,
            'data'=>$data
        ];
        
        $socketio = new SocketIO();
        if ($socketio->send($this->baseUri, 3000 , 'pushData', json_encode($arr))){
            u::insertSimpleRow( array(
                'user_id'=>$user_id,
                'event'=>$event,
                'created_at'=>date('Y-m-d H:i:s'),
                'data'=>json_encode($data)
            ),'log_socket');
            echo 'we sent the message and disconnected: '.json_encode($data);
        } else {
            echo 'Sorry, we have a mistake :\'(';
        }
    }
}
