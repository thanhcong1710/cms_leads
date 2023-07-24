<?php

namespace App\Http\Controllers;

use App\Models\CameraAI;
use App\Providers\UtilityServiceProvider as u;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CameraAIController extends Controller
{
    public function ipn(Request $request){
        u::insertSimpleRow(array(
            'aliasID'=>$request->aliasID,
            'deviceID'=>$request->deviceID,
            'personID' => $request->personID,
            'placeID' => $request->placeID,
            'date' => $request->date,
            'detected_image_url' => $request->detected_image_url,
            'personType' => $request->personType,
            'meta_data' => json_encode($request->input()),
            'created_at' => date('Y-m-d H:i:s')
        ), 'camera_ai_checkin');
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
            $cond .= " AND (p.name LIKE '%$keyword%' OR p.mobile_1 LIKE '%$keyword%' OR p.mobile_2 LIKE '%$keyword%') ";
        }
        if ($end_date !== '') {
            $cond .= " AND c.date < '$end_date 23:59:59' ";
        }
        if ($start_date !== '') {
            $cond .= " AND c.date > '$start_date 00:00:00'";
        }
        if (!empty($branch_id)) {
            $cond .= " AND s.branch_id IN (".implode(",",$branch_id).")";
        }
        
        $order_by = " ORDER BY c.id DESC ";
        
        $total = u::firstCRM("SELECT count(id) AS total FROM camera_ai_checkin AS c WHERE $cond");
        
        $list = u::queryCRM("SELECT c.*
            FROM camera_ai_checkin AS c WHERE $cond $order_by $limitation");
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
}
