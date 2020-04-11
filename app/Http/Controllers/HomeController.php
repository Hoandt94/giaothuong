<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shop;
use App\Admin\Thread;
use App\Admin\Product;
use App\Admin\UserReq;
use App\Admin\Post;
use App\Admin\Page;
use App\Admin\Image;
use App\Admin\Category;
use App\Admin\Contact;
use Illuminate\Support\Facades\Storage;
use App\Admin\TypeProduct;
use App\Http\Requests\ContactRequest as ContactRequest;
use App\Admin\Webinfo;

use Session;

class HomeController extends Controller
{
    public function index()
    {
        $page = Page::where('slug','trang-chu')->first();
        if($page == null) return abort(404);
        $seo      = $page->seo();
        $banners  = $page->banner()->where('status',1)->get();
        $products = Product::where('type',1)->where('type_product_id_2', 1)->where('status',1)->orderby('pos')->get();
        $services = Product::where('type',2)->where('type_product_id_2', 1)->where('status',1)->orderby('pos')->get();
        $shops    = Shop::orderby('pos')->where('type',1)->get();
        $cats     = Category::where('status',1)->where('noi_bat',1)->orderby('created_at', 'desc')->take(2)->get();
        $info     = Webinfo::where('name','thong-tin-chung')->first();
        if($info == null) abort(404);
        $info = json_decode($info->content);
        return view('front-end.page.index',['page'=>$page, 'seo'=>$seo, 'banners'=>$banners, 'products'=>$products, 'services'=>$services, 'shops'=>$shops, 'cats'=>$cats, 'info'=>$info]);
    }

    public function search($s)
    {
        $text = mb_strtolower($s, 'UTF-8');
        $products = Product::whereRaw('lower(name) like (?)',["%{$text}%"])->where('status',1)->paginate(10);
        $count = Product::where('status',1)->count();
        $types_product = TypeProduct::where('status',1)->where('type',1)->get();
        return view('front-end.products.list-by-category',['products'=>$products, 'page'=>'search', 'count'=>$count, 'types'=>$types_product, 't_search'=>$s]);
    }

    public function getListPost($slug)
    {
        $cat = Category::where('status',1)->where('slug',$slug)->first();
        if($cat == null) return abort(404);
        $posts = $cat->posts()->where('status',1)->orderby('created_at', 'desc')->paginate(10);
        return view('front-end.post.list',['posts'=>$posts, 'cat'=>$cat]);
    }

    public function getListVideo()
    {
        $page = Page::where('slug','videos')->first();
        if($page == null) return abort(404);
        $videos = Image::where('type',2)->where('status',1)->paginate(10);
        return view('front-end.video.list',['videos'=>$videos, 'page'=>$page]);
    }

    public function getListImage()
    {
        $albums = Storage::disk('album')->directories();
        $data = [];
        foreach ($albums as $key => $value) {
            $files = Storage::disk('album')->files($value);
            if($files != null && sizeof($files) > 0) $data[$value] = config('admin.base_url').'FILES/source/album/'.$files[0];
        }
        return view('front-end.image.list',['data'=>$data]);
    }

    public function getDetailPost($slug) {
        $post = Post::where('status',1)->where('slug', $slug)->first();
        if($post == null) abort(404);
        $cat = $post->category;
        if($cat == null) abort(404);
        return view('front-end.post.detail')->with(compact(['post', 'cat']));
    }

    public function getPageDetail($slug) {
        $post = Page::where('slug', $slug)->first();
        if($post == null) abort(404);
        return view('front-end.post.detail')->with(compact(['post']));
    }

    public function getDetailImage($name)
    {
        $files = Storage::disk('album')->files($name);
        foreach ($files as $key => $value) {
           $f_name = str_replace($name, "", $value);
           $data[config('admin.base_url').'FILES/source/album/'.$value] = $f_name;
        }
        return view('front-end.image.detail',['data'=>$data, 'name'=>$name]);
    }

    public function getContact()
    {
        return view('front-end.page.contact');
    }

    public function listShop()
    {
        $count = Product::where('status',1)->count();
        $types_product = TypeProduct::where('status',1)->where('type',1)->get();
        $shops = Shop::orderby('point','desc')->where('status',1)->paginate(10);
        return view('front-end.shop.list',['count'=>$count, 'types'=>$types_product, 'shops'=>$shops]);
    }

    public function showShop($username){
        $count = Product::where('status',1)->count();
        $types_product = TypeProduct::where('status',1)->where('type',1)->get();
        $post = Shop::where('status',1)->where('username', $username)->first();
        if($post == null){
            return back();
        }
        return view('front-end.shop.single-cssx',['post'=>$post,'count'=>$count, 'types'=>$types_product]);
    }

    public function postContact(ContactRequest $request)
    {
        $data = $request->all();
        //$tmp = $request->files;
        // $arr_file = [];
        // foreach($tmp as $file){
        //     foreach($file as $key=>$value){
        //         $name_file = time().'.'.$value->getClientOriginalExtension();
        //         $value->storeAs('contacts/'.$data['phone'], $name_file);
        //         $arr_image[] = $name_file;
        //     }
        // }
        // $str_file = implode(";", $arr_file);
        // $data['file'] = $str_file;
        $data['status'] = 0;
        Contact::create($data);
        Session::flash('success-user-contact','Thông tin của bạn đã được gửi đến BQT Hệ thống. Vui lòng chờ phản hồi từ email hoặc số điện thoại.');
        return redirect()->route('contact.get');
    }

    public function sendReq(Request $request)
    {
        $data = $request->all();
        $data['status'] = 0;
        UserReq::create($data);
        $product = Product::find($request->product_id);
        if($product == null) return abort(404);
        $shop = $product->shop;
        if($shop == null) return abort(404);
        $email = $shop->email;
        $user = auth()->user();
        $title = '[NÔNG SẢN THANH HÓA] Yêu cầu mua sản phẩm : '.$product->name;
        $subject = '[NÔNG SẢN THANH HÓA] Yêu cầu mua sản phẩm : '.$product->name;
        $content = 'Khách hàng: '.$request->name. '<br>';
        $content = $content.'Số điện thoại: '.$request->phone. '<br>';
        $content = $content.'Email: '.$request->email. '<br>';
        $content = $content.'Địa chỉ: '.$request->address. '<br>';
        $content = $content.'Nội dung: '.$request->content. '<br>';
        send_mail($email, $title, $subject, $content);
         Session::flash('success','Gửi yêu cầu thành công !');
        return redirect()->route('home');
    }


}
