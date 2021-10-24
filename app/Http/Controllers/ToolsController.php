<?php

namespace App\Http\Controllers;

use App\Providers\UtilityServiceProvider as u;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToolsController extends Controller
{
    public function downloadReCord()
    {
        $last_time = date('Y-m-d h:i:s',time()-3600);
        $list_call = u::query("SELECT * FROM voip24h_data WHERE process_data = 0 AND created_at>'$last_time' ORDER BY id DESC");
        foreach($list_call AS $row){
            $voipControll = new VoipController();
            $response = $voipControll->getDataCallId($row->callid);
            $data = json_decode($response);
            if(isset($data->result->data[0]->download)){
                $dir = __DIR__.'/../../../public/static/voip/'. date('Y_m').'/';
                $dir_file = 'static/voip/'. date('Y_m').'/';
                if(!file_exists($dir)){
                    mkdir($dir);
                }
                $file_name = $row->callid.".wav";
                $file_name_mp3 = $row->callid.".mp3";
                if(!file_exists($dir.$file_name)){
                    file_put_contents($dir.$file_name, fopen($data->result->data[0]->download, 'r'));
                    shell_exec('ffmpeg -i ' . $dir.$file_name . ' ' . $dir.$file_name_mp3 . ''); 
                    u::updateSimpleRow(array('meta_data'=>$response,'process_data'=>1,'link_record'=>$dir_file.$file_name_mp3),array('callid'=>$row->callid),'voip24h_data');
                }
            }
        }
        return "ok";
    }
}
