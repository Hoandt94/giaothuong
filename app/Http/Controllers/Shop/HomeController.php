<?php

namespace App\Http\Controllers\Shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Admin\CheckOut;
use App\Admin\UserReq;

class HomeController extends Controller
{

    public function listCheckOut(){
        $shop = auth()->guard('shop')->user();
        $data = $shop->check_out()->get();
        return view('back-end.shop.checkout.list',['data'=>$data]);
    }

    public function listReq(){
        $shop = auth()->guard('shop')->user();
        $arr_product = $shop->products()->where('status',1)->get()->pluck('id')->all();
        $data = UserReq::wherein('product_id', $arr_product)->get();
        return view('back-end.shop.req.list',['data'=>$data]);
    }
}
