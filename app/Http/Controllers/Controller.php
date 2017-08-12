<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\Route;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct() {
        $this->middleware(function ($request, $next) {
            $menus = [];
            $user = Auth::user();
            if (File::exists(base_path('resources/laravel-admin/menus.json'))) {
                $menus = json_decode(File::get(base_path('resources/laravel-admin/menus.json')));
                $path = '/'.$request->path();
                foreach($menus as $menu){
                    $found = false;
                    foreach($menu as $menuItem){
                        foreach($menuItem->items as $item){
                            if($item->url==$path){
                            $found = true;
                            if(isset($item->viewPerm) && !$user->can($item->viewPerm)){
                                Session::flash('flash_message', 'You do not have access to this resource');
                                return redirect('admin');
                            }
                            
                            break;
                        }
                       }
                       if($found)
                           break;
                    }
                    if($found)
                        break;
                }
            }
            return $next($request);
        });
    }
}
