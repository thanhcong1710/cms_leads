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
        $last_time = date('Y-m-d H:i:s',time()- 24*60*60);
        $list_call = u::query("SELECT id, data_id FROM cms_customer_care WHERE get_data_call =0 AND data_id IS NOT NULL AND created_at>'$last_time' ORDER BY id DESC LIMIT 10");
        foreach($list_call AS $row){
            $pa_cdr_data = u::first("SELECT * FROM pa_cdr_data WHERE id=".(int)$row->data_id);
            if($pa_cdr_data){
                $data_request = [
                    'api_key' => 'f2966f069e0c637f438a1e87b8b6a928',
                    'recording_file' => data_get($pa_cdr_data, 'pa_recordingfile'),
                ];
            
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://crm.pavietnam.vn/api/playRecording.php');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data_request);
                $result = curl_exec($ch);
        
                $dir = __DIR__.'/../../../public/static/voip/'. date('Y_m').'/';
                $dir_file = 'static/voip/'. date('Y_m').'/';
                if(!file_exists($dir)){
                    mkdir($dir);
                }
                $file_name_mp3 = 'pa_cdr_'.$row->data_id.".wav";

                $result = file_put_contents($dir.$file_name_mp3,$result);
                if($result){
                    u::updateSimpleRow(array('get_data_call'=>1,'attached_file'=>$dir_file.$file_name_mp3),array('id'=>$row->id),'cms_customer_care');
                }
            }
        }
        u::query("INSERT INTO log_jobs (`action`, created_at) VALUES ('jobsDownloadVoip','".date('Y-m-d H:i:s')."')");
        return "ok";
    }
    
}
