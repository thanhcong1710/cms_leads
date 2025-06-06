<?php

namespace App\Http\Controllers;

use App\Models\SocketIO;
use App\Providers\UtilityServiceProvider as u;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\CurlServiceProvider as curl;
use Illuminate\Support\Facades\Log;

class VoipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $apiKey;
    protected $baseUriCall;
    protected $baseUriSocket;
    public function __construct()
    {
        // $this->apiKey = "f2966f069e0c637f438a1e87b8b6a928";
        // $this->baseUriSocket = "103.226.250.52";
        // $this->baseUriCall = "https://crm.pavietnam.vn";
        $this->baseUriCall = "https://voip.apps.vn";
        $this->apiKey = "eec23ce1aac22627bb6d1b3115c8518a";
    }
    // public function makeToCall($phone,$sip=0)
    // {

    //     $header=[];
    //     $method = "POST";
    //     $url = sprintf('%s/api/callNow.php',$this->baseUriCall);
    //     $data_request = [
    //         'api_key' => $this->apiKey,
    //         'extension' => $sip,
    //         'phone'   => $phone,
    //     ];
    
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, 'https://crm.pavietnam.vn/api/callNow.php');
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    //     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    //     curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data_request));
    //     $result = curl_exec($ch);
    //     u::logRequest($url,$method,$header,$data_request,$result,'log_request_outbound');

    //     $res =json_decode($result);
    //     if(data_get($res, 'code') == 1000){
    //         $id = u::insertSimpleRow(array(
    //             'phone' => $phone,
    //             'sip_id' => $sip,
    //             'created_at' => date('Y-m-d H:i:s')
    //         ), 'pa_cdr_data');
    //         return [
    //             'status'=>1,
    //             'call_id'=> $id,
    //         ];
    //     } else{
    //         return [
    //             'status'=>0,
    //             'message'=> 'Thực hiện cuộc gọi thất bại, vui lòng thử lại'
    //         ];
    //     }
        
    // }
    // public function getCDRReport($from_date)
    // {
    //     $data_request = [
    //         'api_key' => $this->apiKey,
    //         'to_date' => '',
    //         'duration_max' => '',
    //         'from_date' => $from_date,
    //         'limit'   => '',
    //         'destination' => '',
    //         'duration_min' => '',
    //         'source'  => '',
    //         'status'  => '',
    //         'page'    => '',
    //         'from'    => '',
    //     ];
    
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, 'https://crm.pavietnam.vn/api/getCDRReport.php');
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    //     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    //     curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data_request));
    //     $result = curl_exec($ch);
    //     u::logRequest('https://crm.pavietnam.vn/api/getCDRReport.php','POST',[],$data_request,$result,'log_request_outbound');
    //     $res =json_decode($result);
    //     return $res;
    // }

    // Document VitalPBX https://documenter.getpostman.com/view/5481262/S17rvTgc?version=latest#feb244c3-1d4d-4e89-bbc1-0c54f6df2561

    public function makeToCall($phone,$sip=0)
    {

        $header = array(
            'app-key: '.$this->apiKey,
            'tenant: 1',
            'Content-Type: application/json',
            'Cookie: HttpOnly; HttpOnly; HttpOnly'
        );
        $method = "POST";
        $url = sprintf('%s/api/v2/core/click_to_call',$this->baseUriCall);
        $data_request = [
            'caller' => $sip,
            'callee'   => $phone,
            'cos_id'   => 1,
        ];
    
        $res = curl::curl($url, $method,$header,$data_request);
        u::logRequest($url,$method,$header,$data_request,$res,'log_request_outbound');
        $res = json_decode($res);

        if(data_get($res, 'status') == 'success'){
            $id = u::insertSimpleRow(array(
                'phone' => $phone,
                'sip_id' => $sip,
                'created_at' => date('Y-m-d H:i:s')
            ), 'pa_cdr_data');
            return [
                'status'=>1,
                'call_id'=> $id,
            ];
        } else{
            return [
                'status'=>0,
                'message'=> 'Thực hiện cuộc gọi thất bại, vui lòng thử lại'
            ];
        }
        
    }
    public function getCDRReport($sip_id)
    {
        $header = array(
            'app-key: '.$this->apiKey,
            'tenant: 1'
        );
        $method = "GET";
        $params = [
            'extensions' => 411,
            'calltype' => 3,
            'start_date' => time() - 3600,
            'end_date' => time(),
            'limit'=>'10000',
            'order_by'=>'calldate',
            'order_type'=>'desc',
            'disposition'=> 'ANSWER',
        ];
        
        $queryString = http_build_query($params);
        $url = sprintf('%s/api/v2/cdr?%s',$this->baseUriCall,$queryString);
        $result = curl::curl($url, $method, $header);
        $res =json_decode($result);
        return $res;
    }
}
