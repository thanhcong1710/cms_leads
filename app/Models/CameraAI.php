<?php
namespace App\Models;
use App\Models\APICode;
use App\Providers\CurlServiceProvider as curl;
use App\Providers\UtilityServiceProvider as u;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\DB;

class CameraAI
{
    private $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjczNzQzNTQ2NDE2OTAxNzA1NTkiLCJlbWFpbCI6InRydW5nLm5ndXllbkBjbXNlZHUudm4iLCJjbGllbnRfaWQiOiIxMTIyNDUwMDMzYWNiMzlhYWY4Y2Q4NDRjZWI2NmEwMyIsInR5cGUiOiJhdXRob3JpemF0aW9uX2NvZGUiLCJpYXQiOjE2OTAxODQ0NDksImV4cCI6MTcyMTcyMDQ0OX0.dXErf-Cdm2hzelNC011cGXua-omI8VrLPN6sgiGvfLQ';
    
    public function registerByUrl($student_id){
        $student_info = u::firstCRM("SELECT s.name,s.id AS student_id, s.crm_id, cs.avatar_url ,b.placeID, cs.face_id
            FROM students AS s 
                LEFT JOIN camera_ai_student AS cs ON cs.student_id =s.id 
                LEFT JOIN branches AS b ON s.branch_id=b.id
            WHERE s.id=$student_id");
        $res ="ok";
        if($student_info->avatar_url){    
            if(!$student_info->face_id){
                $url = 'https://partner.hanet.ai/person/registerByUrl';
                $method = 'POST';
                $header = [];
                $params = [
                    'token'=>$this->token,
                    'name'=>$student_info->name,
                    'url'=> env('APP_URL', 'https://lead.cmsedu.vn').$student_info->avatar_url,
                    'aliasID'=>$student_info->student_id,
                    'placeID'=>$student_info->placeID,
                    'title'=>$student_info->student_id,
                    'type'=>1,
                ];
                try {
                    $client = new Client();
                    $res = $client->request($method, $url, [
                        'headers' => $header,
                        'form_params' => $params,
                    ]);
                    $res = json_decode($res->getBody()->getContents(), true);
                    if(data_get($res,'data') && data_get($res['data'],'personID')){
                        u::queryCRM("UPDATE camera_ai_student SET face_id='".data_get($res['data'],'personID')."',updated_at='".date('Y-m-d H:i:s')."' WHERE student_id=$student_id");
                    }
                    u::logRequest($url,$method,$header,$params,$res,'log_request_outbound');
                } catch (RequestException $exception) {
                    u::logRequest($url,$method,$header,$params,$exception->getMessage(),'log_request_outbound');
                }
            }else{
                $url = 'https://partner.hanet.ai/person/updateByFaceUrlByAliasID';
                $method = 'POST';
                $header = ['Content-Type' => 'application/x-www-form-urlencoded'];
                $params = [
                    'token'=>$this->token,
                    'url'=> env('APP_URL', 'https://lead.cmsedu.vn').$student_info->avatar_url,
                    'aliasID'=>$student_info->student_id,
                    'placeID'=>$student_info->placeID
                ];
                try {
                    $client = new Client();
                    $res = $client->request($method, $url, [
                        'headers' => $header,
                        'form_params' => $params,
                    ]);
                    $res = json_decode($res->getBody()->getContents(), true);
                    u::logRequest($url,$method,$header,$params,$res,'log_request_outbound');
                } catch (RequestException $exception) {
                    u::logRequest($url,$method,$header,$params,$exception->getMessage(),'log_request_outbound');
                }
                
            }
        }
        return $res;
    }
}