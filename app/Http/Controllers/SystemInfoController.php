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
    public function getAllMethods(){
        $data = u::query("SELECT * FROM cms_contact_methods WHERE status=1");
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
}
