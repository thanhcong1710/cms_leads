<?php

namespace App\Console\Commands;

use App\Http\Controllers\VoipController;
use App\Models\Sms;
use Illuminate\Console\Command;
use App\Providers\UtilityServiceProvider as u;
use Illuminate\Http\Request;

class JobsDownloadCdrPA extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobsDownloadCdrPA:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'jobsDownloadCdrPA';

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
        $voipControll = new VoipController();
        $date = Date('Y-m-d');
        $data_cdr = $voipControll->getCDRReport($date);
        if(data_get($data_cdr,'code') == 1000){
            $list_cdr = data_get($data_cdr, 'data');
            if ($list_cdr){
                $list_cdr = json_decode($list_cdr);
                rsort($list_cdr);
            }
            foreach ($list_cdr AS $cdr){
                $sip_id = data_get($cdr, 'cnum');
                $phone = data_get($cdr, 'dst');
                $pa_uniqueid = data_get($cdr, 'uniqueid');
                $pa_disposition = data_get($cdr, 'disposition');
                $pa_duration = data_get($cdr, 'duration');
                $pa_billsec = data_get($cdr, 'billsec');
                $pa_recordingfile = data_get($cdr, 'recordingfile');
                $pa_calldate = data_get($cdr, 'calldate');
                $check_exit = u::first("SELECT id FROM pa_cdr_data WHERE pa_uniqueid='$pa_uniqueid' LIMIT 1");
                $end_check = date('Y-m-d H:i:s', strtotime($pa_calldate)+30);
                if (!$check_exit) {
                    $data_crm = u::first("SELECT id FROM pa_cdr_data WHERE sip_id ='$sip_id' AND phone='$phone' AND created_at < '$end_check' AND created_at > '$pa_calldate' AND status=0");
                    if($data_crm) {
                        u::updateSimpleRow(array(
                            'pa_uniqueid' => $pa_uniqueid,
                            'pa_disposition' => $pa_disposition,
                            'pa_duration' => $pa_duration,
                            'pa_billsec' => $pa_billsec,
                            'pa_recordingfile' => $pa_recordingfile,
                            'status' => 1,
                        ), array('id'=>data_get($data_crm, 'id')), 'pa_cdr_data');
                    }
                }
            }
        }

        // $last_time = date('Y-m-d H:i:s',time()- 60*60);
        // $list_call = u::query("SELECT id, data_id FROM cms_customer_care WHERE get_data_call =0 AND data_id=2 AND created_at>'$last_time' ORDER BY id DESC");
        // foreach($list_call AS $row){
        //     $call_info = u::first("SELECT pa_recordingfile FROM pa_cdr_data WHERE id=".(int)data_get($row,'data_id'));
        //     $pa_recordingfile = data_get($call_info, 'pa_recordingfile');

        //     if($pa_recordingfile){
        //         $arr_record_file = explode('/', $pa_recordingfile);
        //         $file_name = $arr_record_file[count($arr_record_file)-1];
        //         $dir = __DIR__.'/../../../public/static/voip/'. date('Y_m').'/';
        //         $dir_file = 'static/voip/'. date('Y_m').'/';
        //         if(!file_exists($dir)){
        //             mkdir($dir);
        //         }
        //         try {
        //             $url = "https://crm.pavietnam.vn/cloud-phone/play-audio?file=symlink/monitor/".date('Y/m/d')."/".'out-0389941902-1410-20241106-142308-1730877784.25070.wav';   // Replace with your file URL
        //             $savePath = $dir.$file_name;
        //             $url ="https://erp.congnghegiaoduc.com/images/logo.png";
        //             $savePath = $dir.'logo.png';
        //                 // Specify the path to save the file

        //              file_put_contents($savePath, file_get_contents($url));
        //             // Initialize a cURL session
        //             // $ch = curl_init($url);
        //             // $fp = fopen($savePath, 'wb');
        //             // if (!$fp) {
        //             //     die("Không thể mở file để ghi. Vui lòng kiểm tra quyền và đường dẫn.");
        //             // }
        //             // $ch = curl_init($url);
        //             // curl_setopt($ch, CURLOPT_FILE, $fp);
        //             // curl_setopt($ch, CURLOPT_HEADER, 0);
        //             // curl_setopt($ch, CURLOPT_FAILONERROR, true);
        //             // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Theo dõi chuyển hướng
        //             // curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; cURL)");

        //             // // Thực thi cURL và kiểm tra lỗi
        //             // if (!curl_exec($ch)) {
        //             //     echo "Lỗi khi tải tệp: " . curl_error($ch) . " (HTTP code: " . curl_getinfo($ch, CURLINFO_HTTP_CODE) . ")";
        //             // } else {
        //             //     echo "Tải tệp thành công!";
        //             // }

        //             // Đóng cURL và tệp
        //             // curl_close($ch);
        //             // fclose($fp);


        //             // Execute the cURL session
        //             // if (curl_exec($ch)) {
        //             //     u::updateSimpleRow(array('get_data_call'=>1,'attached_file'=>$dir_file.$file_name),array('id'=>$row->id),'cms_customer_care');
        //             // } 
        //         } catch (\Throwable $th) {
        //             //throw $th;
        //         }  
                
        //     }
        // }
        u::query("INSERT INTO log_jobs (`action`, created_at) VALUES ('jobsDownloadCdrPA','".date('Y-m-d H:i:s')."')");
        return "ok";
    }
    
}
