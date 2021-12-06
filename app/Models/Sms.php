<?php
namespace App\Models;
use App\Models\APICode;
use App\Providers\CurlServiceProvider as curl;
use App\Providers\UtilityServiceProvider as u;
use Illuminate\Support\Facades\DB;

class Sms
{
    // dvkh@cmsedu.vn
    private $api_key = 'BF6EE459A0011379C729815971B451';
    private $secret_key = 'CFBFEBE8DBCDAADD52D4901329BF35';

    //marketing@cmsedu.vn
    // private $api_key = '42F400A8FCCF8A7A8800BECF88F681';
    // private $secret_key = '95CB6E1A65C60B2ECC0DD2884FD710';

    public function sendSms($phone,$content,$creator_id=0){
        $api_key = $this->api_key;
        $secret_key = $this->secret_key;
        $url = 'http://rest.esms.vn/MainService.svc/json/SendMultipleMessage_V4_post_json/';
        $method = 'POST';
        $header = [];
        $params = [
            'ApiKey'=>$api_key,
            'SecretKey'=>$secret_key,
            'IsUnicode'=> 0,
            'SmsType'=>2,
            'Brandname'=>'CMSEDU',
            'Phone'=>$phone,
            'Content'=>u::convert_name($content),
        ];
        $res = curl::curl($url, $method, $header, $params);
        
        DB::table('log_sms')->insert([
            'phone'=>$phone,
            'url' => $url,
            'method' => $method,
            'header' => json_encode($header),
            'params' => json_encode($params),
            'created_at' => date('Y-m-d H:i:s'),
            'response' => $res,
            'creator_id'=>$creator_id,
            'status'=>1
        ]);

        return $res;
    }
}