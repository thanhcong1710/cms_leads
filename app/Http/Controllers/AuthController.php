<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Providers\UtilityServiceProvider as u;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Providers\CurlServiceProvider as curl;
class AuthController extends Controller
{ 
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register','singleSignOn','getLoginRedirect']]);
    }
  
    /**
     * Register new user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request){
        $validate = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:4|confirmed',
        ]);        
        if ($validate->fails()){
            return response()->json([
                'status' => 'error',
                'errors' => $validate->errors()
            ], 422);
        }        
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->status = 1;
        $user->save();       
        return response()->json(['status' => 'success'], 200);
    } 

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token, $request->email);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $email)
    {
        $user = u::first("SELECT *,menuroles AS roles FROM users WHERE email='$email' AND status=1");
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'roles' => $user->roles,
            'user_id'=>$user->id,
            'branch_id'=>$user->branch_id,
            'rules_setting'=>$user->rules_setting,
            'user_info'=>[
                'name'=>$user->name,
                'hrm_id'=>$user->hrm_id,
                'phone'=>$user->phone,
                'email'=>$user->email,
                'sip_id'=>$user->sip_id,
            ]
        ]);
    }
    public function singleSignOn(Request $request){
        $hrm_id = $request->hrm_id;
        $token = $request->token;
        $key ="CMS@abcd1234";
        if($token == md5($key.$hrm_id)){
            $user_info = u::first("SELECT id FROM users WHERE hrm_id='$hrm_id' AND status=1");
            if($user_info){
                $user = User::find( $user_info->id);
                $token = JWTAuth::fromUser($user);
                return $this->respondWithToken($token, $user->email);
            }else{
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }else{
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
    public function switchSystem(Request $request){
        $key ="CMS@abcd1234";
        if(env('APP_ENV', 'staging')=='production'){
            $tmp_link_redirect = 'https://account.congnghegiaoduc.com/#/single-sign-on/';
        }else{
            $tmp_link_redirect = 'https://stg-account.congnghegiaoduc.com/#/single-sign-on/';
        }
        return response()->json(['link_redirect' => $tmp_link_redirect.$request->user()->hrm_id."/".md5($key.$request->user()->hrm_id)]);
    }
    protected function logoutSingleSignOn(Request $request){
        $key ="CMS@abcd1234";
        $method = "GET";
        if(env('APP_ENV', 'staging')=='production'){
            $tmp_link = 'https://account.congnghegiaoduc.com/api/logout-single-sign-on?hrm_id=';
        }else{
            $tmp_link = 'https://stg-account.congnghegiaoduc.com/api/logout-single-sign-on?hrm_id=';
        }
        $url = $tmp_link.$request->user()->hrm_id."/".md5($key.$request->user()->hrm_id);
        $res = curl::curl($url, $method);
        return $res;
    }
    public function getLoginRedirect(){
        if(env('APP_ENV', 'staging')=='production'){
            $data = 'https://account.congnghegiaoduc.com/#/login';
        }else{
            $data = 'https://stg-account.congnghegiaoduc.com/#/login';
        }
        return response()->json($data);
    }
}