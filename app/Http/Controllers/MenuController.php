<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Menus\GetSidebarMenu;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $user = auth()->user();
            if($user && !empty($user)){
                $roles =  $user->menuroles;
            }else{
                $roles = '';
            }
        } catch (Exception $e) {
            $roles = '';
        }   
        if($request->has('menu')){
            $menuName = $request->input('menu');
        }else{
            $menuName = 'sidebar menu';
        }
        $menus = new GetSidebarMenu();
        return response()->json( $menus->get( $roles, $menuName ) );
    }
    public function cameraAI(Request $request){
        $menu = array(
            '0' => [
                "id" => 66,
                "slug" => "link",
                "name" => "Đổi Hệ Thống",
                "href" => "/switch-system",
                "hasIcon" => true,
                "icon" => "cil-sync",
                "iconType" => "coreui"
            ],
            '1' => [
                "id" => 67,
                "slug" => "link",
                "name" => "Danh sách sự kiện",
                "href" => "/camera-ai",
                "hasIcon" => true,
                "icon" => "cil-notes",
                "iconType" => "coreui"
            ],
            '2' => [
                "id" => 67,
                "slug" => "link",
                "name" => "Khởi tạo dữ liệu",
                "href" => "/camera-register",
                "hasIcon" => true,
                "icon" => "cil-cloud-upload",
                "iconType" => "coreui"
            ],
        );
        return response()->json( $menu );
    }

}

