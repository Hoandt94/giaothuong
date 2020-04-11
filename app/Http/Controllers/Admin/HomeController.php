<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Admin\CheckOut;
use App\Admin\UserReq;

class HomeController extends Controller
{
    //
    public function adminHome(){
    	return view('back-end.pages.home');
    }

    public function createSlug(Request $request)
    {
    	$slug = Str::slug($request->input('str'), '-');
        return response()->json(array('slug'=>$slug), 200);
    }

    public function listCheckOut(){
    	$data = CheckOut::all();
    	return view('back-end.admin.checkout.list',['data'=>$data]);
    }

    public function listReq(){
    	$data = UserReq::all();
    	return view('back-end.admin.req.list',['data'=>$data]);
    }
}
