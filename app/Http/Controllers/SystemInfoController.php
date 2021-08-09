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
}
