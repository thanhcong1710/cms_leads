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
        if($user->hasRole('admin') || $user->hasRole('Supervisor')){
            $users_manager="";
            foreach($list_users AS $row){
                $users_manager.=$users_manager ? ",".$row->id : $row->id;
            }
        }elseif($user->hasRole('Marketing')){
            $list_users = u::query("SELECT u.id,u.manager_id FROM users AS u WHERE u.status=1 AND
            ((SELECT count(role_id) FROM model_has_roles WHERE model_id=u.id AND role_id=7)>0 OR u.id=$user->id)");
            $users_manager="";
            foreach($list_users AS $row){
                $users_manager.=$users_manager ? ",".$row->id : $row->id;
            }
        }else{
            $users_manager = implode(",",$this->data_tree($list_users,$user->id));
            $users_manager = $user->id.($users_manager?",".$users_manager:"");
        }
        $tmp_users_manager="";
        // if($user->id == 21){
        //     $list_users = u::query("SELECT u.id,u.manager_id FROM users AS u WHERE u.status=1 AND
        //         (SELECT count(role_id) FROM model_has_roles WHERE model_id=u.id AND role_id=7)>0 ");
        //     foreach($list_users AS $row){
        //         $tmp_users_manager.=$tmp_users_manager ? ",".$row->id : $row->id;
        //     }
        //     foreach($list_users AS $row){
        //         $users_manager.=$users_manager ? ",".$row->id : $row->id;
        //     }
        // }
        $request->user_info = (object)array(
            'users_manager' => $users_manager,
            'hrm_id' => $user->hrm_id,
            'tmp_users_manager' => $tmp_users_manager
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
