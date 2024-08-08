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
    protected $baseUriCall;
    protected $baseUriSocket;
    public function __construct()
    {
        $this->baseUriSocket = "103.226.250.52";
        $this->baseUriCall = "https://rsv01.oncall.vn:8887";
    }
    private function getToken(){
        $header=[
            "Content-Type: application/json",
        ];
        $method = "POST";
        $params = array(
            'username' => 'HNCX01058',
            'password' => 'Eh5Gee3Cb3B1',
            'domain' => 'hncx01058.oncall',
        );
        $url = sprintf('%s/api/tokens',$this->baseUriCall);
        $res = curl::curl($url, $method, $header, $params);
        $res =json_decode($res);
        return data_get($res, 'access_token');
    }
    public function makeToCall($phone,$sip=0)
    {
        $token = self::getToken();
        $header=[
            "Authorization: Bearer ".$token
        ];
        $method = "GET";
        $params = array(
            'extension_number' => $sip,
            'password' => $this->getPassByShip($sip),
            'domain' => 'hncx01058.oncall',
            'caller' => $sip,
            'callee' => $phone,
            'send_sdp' =>true,
        );
        $url = $url = sprintf('%s/api/sessions/directly?%s',$this->baseUriCall, http_build_query($params));
        $res = curl::curl($url, $method, $header, []);
        u::logRequest($url,$method,$header,$params,$res,'log_request_outbound');
        $res =json_decode($res);
        if(data_get($res, 'id')){
            return [
                'status'=>1,
                'call_id'=> data_get($res, 'id')
            ];
        } else{
            return [
                'status'=>0,
                'message'=> 'Thực hiện cuộc gọi thất bại, vui lòng thử lại'
            ];
        }
    }
    private function getPassByShip($sip){
        $sip= (int)$sip;
        $sip_info= u::first("SELECT * FROM oncall_extension_has_pass WHERE ext = $sip");
        return $sip_info ? $sip_info->password : '';
    }
    
    public function oncallWebhook(Request $request){
        Log::info("message",['data'=>$request->input()]);
        return response()->json("ok");
    }

    public function getDataRecordCallId($data_id){
        $token = self::getToken();
        $header=[
            "Authorization: Bearer ".$token
        ];
        $method = "GET";
        $url = $url = sprintf('%s/api/recordings/%s',$this->baseUriCall, $data_id);
        $res = curl::curl($url, $method, $header, []);
        $res =json_decode($res);
        u::logRequest($url,$method,$header,[],$res,'log_request_outbound');
        return $res;
    }
}
