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
        $meta_data = json_encode($request->all(), JSON_UNESCAPED_UNICODE);
        $created_at = date('Y-m-d H:i:s');
        u::insertSimpleRow( array(
            'type'=>'webhook',
            'response'=>$meta_data,
            'created_at'=>$created_at
        ),'voip24h_respose');
        return response()->json("ok");
    }
}
