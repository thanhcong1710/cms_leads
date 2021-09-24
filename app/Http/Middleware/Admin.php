<?php

namespace App\Http\Middleware;
use App\Providers\UtilityServiceProvider as u;
use Closure;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        if(empty($user)){
            return response()->json(['message' => 'Unauthenticated. Admin role required'], 401);
        }
        $list_users = u::query("SELECT id,manager_id FROM users WHERE status=1");
        if($user->hasRole('admin')){
            $users_manager="";
            foreach($list_users AS $row){
                $users_manager.=$users_manager ? ",".$row->id : $row->id;
            }
        }else{
            $users_manager = implode(",",$this->data_tree($list_users,$user->id));
            $users_manager = $user->id.($users_manager?",".$users_manager:"");
        }
        $request->user_info = (object)array(
            'users_manager' => $users_manager
        );
        return $next($request);
    }
    public function data_tree($data, $manager_id = 0){
        $result = [];
        foreach($data as $k=> $item){
            if($item->manager_id == $manager_id){
                $result[] = $item->id;
                unset($data[$k]);
                $child = $this->data_tree($data, $item->id);
                $result = array_merge($result, $child);
            }
        }
        return $result;
    }
}
