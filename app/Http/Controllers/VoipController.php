<?php

namespace App\Http\Controllers;

use App\Models\SocketIO;
use App\Providers\UtilityServiceProvider as u;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\CurlServiceProvider as curl;

class VoipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->baseUri = "35.187.235.207";
        $this->voip24 = "http://dial.voip24h.vn/dial";
        $this->voip24_key = "cb7320a02d1bf15651002cac0fe56523e13d7298";
        $this->voip24_secret = "0e43c1bc6caeb10fc22a3ca43cc29b99";
    }
    public function webhook(Request $request)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        u::insertSimpleRow( array(
            'type'=>'webhook',
            'response'=>json_encode($data, true),
            'callid'=>$data['callid'],
            'state'=>$data['state'],
            'created_at'=>date('Y-m-d H:i:s'),
        ),'voip24h_respose');
        $obj = (object)$data;
        if($obj->state == 'Cdr'){
            $cdr = (object)$obj->cdr;
            if($obj->type =="inbound"){
                $phone = $cdr->source;
                $disposition = $cdr->disposition;
            }else{
                $phone = $obj->phone;
                if($cdr->disposition=='ANSWERED'){
                    $check_info = u::first("SELECT count(id) AS total FROM voip24h_respose WHERE callid= '$obj->callid' AND state='Up'");
                    $disposition = $check_info->total>0 ? 'ANSWERED' :'NO ANSWER';
                }else{
                    $disposition = $cdr->disposition;
                }
            }
            
            $data_id = u::insertSimpleRow( array(
                'callid'=>$obj->callid,
                'phone'=>$phone,
                'type'=>$obj->type,
                'sip_id'=>$obj->extend,
                'start_time'=>$cdr->starttime ? $cdr->starttime : NULL,
                'answer_time'=>$cdr->answertime ? $cdr->answertime : NULL,
                'end_time'=>$cdr->endtime ? $cdr->endtime : NULL,
                'duration'=>$cdr->duration,
                'disposition'=>$disposition,
                'created_at'=>date('Y-m-d H:i:s'),
            ),'voip24h_data');
            $parent_info = u::first("SELECT id FROM cms_parents WHERE (mobile_1='$phone' OR mobile_2='$phone')");
            $user_info = u::first("SELECT id FROM users WHERE sip_id='".(int)$obj->extend."'");
            if($data_id && $parent_info && $user_info){
                $care_id = u::insertSimpleRow( array(
                    'parent_id'=>$parent_info->id,
                    'note'=>'',
                    'created_at'=>date('Y-m-d H:i:s'),
                    'creator_id'=>$user_info->id,
                    'method_id'=>1,
                    'care_date'=>$cdr->starttime ? $cdr->starttime : NULL,
                    'data_id'=>$data_id,
                    'data_state'=>$disposition,
                    'phone'=>$phone,
                ),'cms_customer_care');
                $this->socketIo($user_info->id,'call_end',array('care_id'=>$care_id,'parent_id'=>$parent_info->id));
            }
        }elseif($obj->state == 'Ring' && $obj->type=="inbound"){
            $user_info = u::first("SELECT id FROM users WHERE sip_id='".(int)$obj->extend."'");
            $parent_info = u::first("SELECT id FROM cms_parents WHERE mobile_1='$obj->phone'");
            if($user_info && $parent_info){ 
                $this->socketIo($user_info->id,'inbound',array('phone'=>$obj->phone));
            }
        }
        
        return response()->json("ok");
    }
    public function makeToCall($phone,$sip=651)
    {
        $method = "GET";
        $http_data = array(
            'voip' => $this->voip24_key,
            'secret' => $this->voip24_secret,
            'sip' => $sip ? $sip : '651',
            'phone' =>$phone
        );
        $url = sprintf('%s?%s',$this->voip24, http_build_query($http_data));
        $res = curl::curl($url, $method);
        u::logRequest($url,$method,[],[],$res,'log_request_outbound');
        return "ok";
    }
    public function socketIo($user_id,$event,$data){

        $arr=[
            'user_id'=>$user_id,
            'event'=>$event,
            'data'=>$data
        ];
        
        $socketio = new SocketIO();
        if ($socketio->send($this->baseUri, 3000, 'pushData', json_encode($arr))){
            echo 'we sent the message and disconnected';
        } else {
            echo 'Sorry, we have a mistake :\'(';
        }
    }
    public function getDataCallId($callid){
        $method = "GET";
        $http_data = array(
            'voip' => $this->voip24_key,
            'secret' => $this->voip24_secret,
            'callid' => $callid,
        );
        $url = sprintf('%s?%s',$this->voip24."/searching", http_build_query($http_data));
        $res = curl::curl($url, $method);
        u::logRequest($url,$method,[],[],$res,'log_request_outbound');
        return $res;
    }
}
