<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\WebinfoRequest as WebinfoRequest;
use App\Admin\Webinfo;
use Session;

class WebinfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function commonInfo(Request $request){
        $page_name = "Thiết lập thông tin chung";
        $flag = 'thong-tin-chung';
        $_route = route('thong-tin-chung.post');
        $_form = 'info';
        $obj  = Webinfo::where('name','thong-tin-chung')->first();
        $content = null;
        if($obj != null) $content = json_decode($obj->content);
        return view('back-end.webinfo.info',['flag'=>$flag, 'page_name'=>$page_name, '_route'=>$_route, '_form'=>$_form, 'content'=>$content]);
    }

    public function postCommonInfo(Request $request){
        $flag = 'thong-tin-chung';
        $data = $request->all();
        if ($data['image_video'] != null && $data['image_video'] != "") {
            $index = strpos($data['image_video'],'FILES/source/');
            if (!$index === false) {
                $data['image_video'] = substr($data['image_video'],$index, strlen($data['image_video']));
            }
        }
        $data = json_encode($data);
        $obj  = Webinfo::where('name','thong-tin-chung')->first();
        if($obj == null) {
           $obj = Webinfo::create(['name'=>'thong-tin-chung', 'content'=>$data]);
        }else $obj->update(['content'=>$data]);
        Session::flash('success-'.$flag,'Thay đổi thiết lập thông tin chung thành công');
        return back();
    }

    public function headerInfo(Request $request){
        $page_name = "Thiết lập Header/ Footer";
        $flag = 'header';
        $_route = route('header.post');
        $_form = 'header';
        $obj  = Webinfo::where('name','header')->first();
        $content = null;
        if($obj != null) $content = json_decode($obj->content);
        return view('back-end.webinfo.info',['flag'=>$flag, 'page_name'=>$page_name, '_route'=>$_route, '_form'=>$_form, 'content'=>$content]);
    }

    public function postHeaderInfo(Request $request){
        $flag = 'header';
        $data = $request->only('logo','banner', 'link_logo', 'link_banner', 'logo_footer', 'link_logo_footer');
        if ($data['logo'] != null && $data['logo'] != "") {
            $index = strpos($data['logo'],'FILES/source/');
            if (!$index === false) {
                $data['logo'] = substr($data['logo'],$index, strlen($data['logo']));
            }
        }
        if ($data['banner'] != null && $data['banner'] != "") {
            $index = strpos($data['banner'],'FILES/source/');
            if (!$index === false) {
                $data['banner'] = substr($data['banner'],$index, strlen($data['banner']));
            }
        }

        if ($data['logo_footer'] != null && $data['logo_footer'] != "") {
            $index = strpos($data['logo_footer'],'FILES/source/');
            if (!$index === false) {
                $data['logo_footer'] = substr($data['logo_footer'],$index, strlen($data['logo_footer']));
            }
        }

        $data = json_encode($data);
        $obj  = Webinfo::where('name','header')->first();
        if($obj == null) {
           $obj = Webinfo::create(['name'=>'header', 'content'=>$data]);
        }else $obj->update(['content'=>$data]);
        Session::flash('success-'.$flag,'Thay đổi thiết lập thông tin header thành công');
        return back();
    }

    public function menu(Request $request){
        $page_name = "Thiết lập menu";
        $flag = 'menu';
        return view('back-end.webinfo.menu',['flag'=>$flag, 'page_name'=>$page_name]);
    }

}
