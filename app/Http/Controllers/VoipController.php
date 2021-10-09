<?php

namespace App\Http\Controllers;

use App\Providers\UtilityServiceProvider as u;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as x;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function webhook(Request $request)
    {
        $request_json = json_decode(file_get_contents('php://input'), true);
        $request_server = json_encode($request_json, true);
        $created_at = date('Y-m-d H:i:s');
        u::insertSimpleRow( array(
            'type'=>'webhook',
            'response'=>$request_server,
            'created_at'=>$created_at
        ),'voip24h_respose');
        return response()->json("ok");
    }
}
