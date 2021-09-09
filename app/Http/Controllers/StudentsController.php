<?php

namespace App\Http\Controllers;

use App\Providers\UtilityServiceProvider as u;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentsController extends Controller
{
    public function add(Request $request)
    {
        $data = u::insertSimpleRow(array(
            'parent_id'=>$request->parent_id,
            'name'=>$request->name,
            'gender' => $request->gender,
            'birthday' => $request->birthday,
            'school_level' => $request->school_level,
            'school' => $request->school,
            'note' => $request->note,
            'created_at' => date('Y-m-d H:i:s'),
            'creator_id' => Auth::user()->id,
        ), 'cms_students');
        return response()->json($data);
    }
    public function getAllDataByParent($parent_id){
        $data = u::query("SELECT s.*, (SELECT name FROM users WHERE id=s.creator_id) AS creator_name
            FROM cms_students AS s WHERE s.parent_id=$parent_id ORDER BY s.id DESC");
        return response()->json($data);
    }
}
