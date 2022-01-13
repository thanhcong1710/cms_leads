<?php

namespace App\Http\Controllers;

use App\Providers\UtilityServiceProvider as u;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SystemInfoController extends Controller
{
    
    public function getAllProvices()
    {
        $data = u::getMultiObject([], 'cms_provinces');
        return response()->json($data);
    }
    public function getDistrictsByProvice($province_id){
        $data = u::getMultiObject(['province_id'=>$province_id], 'cms_districts');
        return response()->json($data);
    }
    public function getAllJobs()
    {
        $data = u::query("SELECT * FROM cms_jobs WHERE status=1");
        return response()->json($data);
    }
    public function getAllSources(){
        $data = u::query("SELECT * FROM cms_sources WHERE status=1");
        return response()->json($data);
    }
    public function getAllSourceDetail(){
        $data = u::query("SELECT * FROM cms_source_detail WHERE status=1");
        return response()->json($data);
    }
    public function getAllMethods(){
        $data = u::query("SELECT * FROM cms_contact_methods WHERE status=1");
        return response()->json($data);
    }
    public function getAllTemplateNote(){
        $data = u::query("SELECT * FROM cms_template_note WHERE status=1");
        return response()->json($data);
    }
    public function getAllBranches(){
        $data = u::query("SELECT * FROM cms_branches WHERE status=1");
        return response()->json($data);
    }
    public function getSchools($province_id, $district_id, $school_level)
    {
        $data = null;
        if ($province_id && $district_id && $school_level) {
            $data = u::query("SELECT `name`, `level`, address, id FROM cms_schools WHERE `level` = '$school_level' AND district_id = $district_id AND province_id = $province_id");
            if (count($data)) {
                foreach($data as $i => $o) {
                    if (mb_strtolower(substr($o->name, 0, strlen($o->level))) != mb_strtolower($o->level)) {
                        $name = $o->name;
                        $o->name = "$o->level $name";
                    }
                }
            }
        }
        return response()->json($data);
    }
    public function getListSourceDetail(Request $request)
    {
        $status = isset($request->status) ? $request->status : '';
        $keyword = isset($request->keyword) ? $request->keyword : '';
        
        $pagination = (object)$request->pagination;
        $page = isset($pagination->cpage) ? (int) $pagination->cpage : 1;
        $limit = isset($pagination->limit) ? (int) $pagination->limit : 20;
        $offset = $page == 1 ? 0 : $limit * ($page-1);
        $limitation =  $limit > 0 ? " LIMIT $offset, $limit": "";
        $cond = " 1 ";
        if($status!==''){
            $cond .= " AND s.status=$status";
        }
        if($keyword!==''){
            $cond .= " AND s.name LIKE '%$keyword%'";
        }
        if(!$request->user()->hasRole('admin')){
            $edit = "IF(".$request->user()->id." = s.creator_id,1,0) AS can_edit";
        }else{
            $edit = "1 AS can_edit";
        }
        $total = u::first("SELECT count(id) AS total FROM cms_source_detail AS s WHERE $cond ");
        $list = u::query("SELECT s.*,$edit 
            FROM cms_source_detail AS s WHERE $cond ORDER BY s.id DESC $limitation");
        $data = u::makingPagination($list, $total->total, $page, $limit);
        return response()->json($data);
    }
    public function addSourceDetail(Request $request)
    {
        $id = u::insertSimpleRow(array(
            'name'=>$request->name,
            'created_at' => date('Y-m-d H:i:s'),
            'creator_id' => Auth::user()->id,
            'status'=>$request->status,
        ), 'cms_source_detail');
        return response()->json($id);
    }
    public function infoSourceDetail(Request $request,$source_detail_id)
    {
        $data = u::first("SELECT * FROM cms_source_detail WHERE id=$source_detail_id");
        return response()->json($data);
    }
    public function updateSourceDetail(Request $request,$source_detail_id)
    {
        $id = u::updateSimpleRow(array(
            'name'=>$request->name,
            'updated_at' => date('Y-m-d H:i:s'),
            'updator_id' => Auth::user()->id,
            'status'=>$request->status,
        ), array('id'=>$source_detail_id),'cms_source_detail');
        return response()->json($id);
    }
}
