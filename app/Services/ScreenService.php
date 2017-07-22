<?php
namespace App\Services;
use App\Transformers\ScreenTransformer;
use Chrisbjr\ApiGuard\Http\Controllers\ApiGuardController;
use App\Http\Models\Screen;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ScreenService extends ApiGuardController{
    public function  fetch_screens($payload){
        $screens = Screen::where('status',1)->orderBy('order','asc')->get();
        return $this->response->withCollection($screens, new ScreenTransformer());
    }
}

