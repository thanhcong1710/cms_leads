<?php

namespace App\Console\Commands;

use App\Http\Controllers\VoipController;
use App\Models\Sms;
use Illuminate\Console\Command;
use App\Providers\UtilityServiceProvider as u;
use Illuminate\Http\Request;

class JobsDownloadVoip extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobsDownloadVoip:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'jobsDownloadVoip';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Request $request)
    {
        $last_time = date('Y-m-d H:i:s',time()- 60*60);
        $list_call = u::query("SELECT id, data_id FROM cms_customer_care WHERE get_data_call =0 AND data_id IS NOT NULL AND created_at>'$last_time' ORDER BY id DESC");
        foreach($list_call AS $row){
            $voipControll = new VoipController();
            $response = $voipControll->getDataRecordCallId($row->data_id);
            if(isset($response->items[0]->file_id) && $response->items[0]->file_id){
                $dir = __DIR__.'/../../../public/static/voip/'. date('Y_m').'/';
                $dir_file = 'static/voip/'. date('Y_m').'/';
                if(!file_exists($dir)){
                    mkdir($dir);
                }
                $file_name = $row->data_id.".wav";
                $file_name_mp3 = $row->data_id.".mp3";
                $file_id = $response->items[0]->file_id;
                $url_download = "https://rsv01.oncall.vn:8887/api/files/$file_id/data";

                $arrContextOptions=array(
                    "ssl"=>array(
                        "verify_peer"=>false,
                        "verify_peer_name"=>false,
                    ),
                );  
                file_put_contents($dir.$file_name, file_get_contents("$url_download", false, stream_context_create($arrContextOptions)));

                shell_exec('ffmpeg -i ' . $dir.$file_name . ' ' . $dir.$file_name_mp3 . ''); 
                u::updateSimpleRow(array('get_data_call'=>1,'attached_file'=>$dir_file.$file_name_mp3),array('id'=>$row->id),'cms_customer_care');
            }else{
                u::updateSimpleRow(array('get_data_call'=>1),array('id'=>$row->id),'cms_customer_care');
            }
        }
        u::query("INSERT INTO log_jobs (`action`, created_at) VALUES ('jobsDownloadVoip','".date('Y-m-d H:i:s')."')");
        return "ok";
    }
    
}
