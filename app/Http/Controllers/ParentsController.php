<?php

namespace App\Http\Controllers;

use App\Providers\UtilityServiceProvider as u;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentsController extends Controller
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
        if(!$request->user()->hasRole('admin')){
            $cond .= " p.owner_id = ".(int)$request->user()->id;
        }
        if ($status !== '') {
            $cond .= " AND p.status=$status";
        }
        if ($keyword !== '') {
            $cond .= " AND (p.name LIKE '%$keyword%' OR p.mobile_1 LIKE '%$keyword%' OR p.mobile_2 LIKE '%$keyword%') ";
        }
        $total = u::first("SELECT count(id) AS total FROM cms_parents AS p WHERE $cond $limitation");
        $list = u::query("SELECT p.*, (SELECT name FROM sources WHERE id=p.source_id) AS source_name,
                (SELECT name FROM users WHERE id=p.owner_id) AS owner_name 
            FROM cms_parents AS p WHERE $cond");
        $data = u::makingPagination($list, $total->total, $page, $limit);
        return response()->json($data);
    }
    public function add(Request $request)
    {
        $data = u::insertSimpleRow(array(
            'name'=>$request->name,
            'email'=>$request->email,
            'mobile_1' => $request->mobile_1,
            'address' => $request->address,
            'province_id' => $request->province_id,
            'district_id' => $request->district_id,
            'gender' => $request->gender,
            'birthday' => $request->birthday,
            'job_id' => $request->job_id,
            'source_id' => $request->source_id,
            'note' => $request->note,
            'created_at' => date('Y-m-d H:i:s'),
            'creator_id' => Auth::user()->id,
        ), 'cms_parents');
        return response()->json($data);
    }
    public function detail($parent_id)
    {
        $data = u::getObject(array('id' => $parent_id), 'cms_parents');
        return response()->json($data);
    }
    public function update(Request $request, $parent_id)
    {
        $data = u::updateSimpleRow(array(
            'name'=>$request->name,
            'email'=>$request->email,
            'mobile_1' => $request->mobile_1,
            'address' => $request->address,
            'province_id' => $request->province_id,
            'district_id' => $request->district_id,
            'gender' => $request->gender,
            'birthday' => $request->birthday,
            'job_id' => $request->job_id,
            'source_id' => $request->source_id,
            'note' => $request->note,
            'updated_at' => date('Y-m-d H:i:s'),
            'updator_id' => Auth::user()->id,
        ), array('id' => $parent_id), 'cms_parents');
        return response()->json($data);
    }
    public function delete($parent_id)
    {
        $data = u::query("DELETE FROM cms_parents WHERE id=$parent_id");
        return response()->json($data);
    }
}