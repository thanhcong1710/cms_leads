<?php

namespace App\Http\Controllers;

use App\Providers\UtilityServiceProvider as u;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentCareController extends Controller
{
    public function add(Request $request)
    {
        $data = u::insertSimpleRow(array(
            'parent_id'=>$request->parent_id,
            'note'=>$request->note,
            'method_id' => $request->method_id,
            'care_date' => date('Y-m-d H:i:s',strtotime($request->care_date)),
            'created_at' => date('Y-m-d H:i:s'),
            'creator_id' => Auth::user()->id,
            'branch_id' => Auth::user()->branch_id,
        ), 'cms_customer_care');
        return response()->json($data);
    }
    public function getAllDataByParent($parent_id){
        $data = u::query("SELECT c.*, (SELECT name FROM users WHERE id=c.creator_id) AS creator_name,
                (SELECT name FROM cms_contact_methods WHERE id=c.method_id) AS method_name ,
                (SELECT type FROM voip24h_data WHERE id= c.data_id) AS type_call,
                (SELECT link_record FROM voip24h_data WHERE id = c.data_id) AS link_record,
                (SELECT name FROM cms_branches WHERE id=c.branch_id) AS branch_name
            FROM cms_customer_care AS c WHERE parent_id=$parent_id ORDER BY c.care_date DESC");
        return response()->json($data);
    }
    public function getInfoCall($care_id){
        $data = u::first("SELECT c.id AS care_id,c.data_state,v.* 
            FROM cms_customer_care AS c 
                LEFT JOIN voip24h_data AS v ON v.id=c.data_id
            WHERE c.id=$care_id");
        return response()->json($data);
    }
    public function updateNoteCare(Request $request){
        u::updateSimpleRow(array('note'=>$request->note),array('id'=>$request->care_id),'cms_customer_care');
        return "ok";
    }
}
