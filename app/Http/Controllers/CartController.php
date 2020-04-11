<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Admin\Product;
use App\Admin\CartDb;
use App\Admin\CheckOut;
use App\Shop;
use Session;
use Cart;

class CartController extends Controller
{
    public function addCartAjax(Request $request){
    	$id = $request->input('product_id');
        $qty = $request->input('num');
    	$product = Product::find($id);
    	Cart::add(array(
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $qty,
                'attributes' => array(
                    'image' => $product->main_image,
                    'code' => $product->product_code,
                    'sale_off' => $product->sale_off,
                    'shop_id' => $product->shop != null ? $product->shop->id : null,
                )
            ));
            $count = Cart::getTotalQuantity();
    	return  response()->json(['count' => $count]);
    }

    public function changeCartAjax(Request $request){
        $id = $request->input('product_id');
        $qty = $request->input('num');
        $type = $request->input('type');
        $product = Product::find($id);
        if($type == 'del'){
               Cart::remove($id);
            }else if($type == 'change'){
                if($qty <= 0)  Cart::remove($id);
                else{
                    Cart::remove($id);
                    Cart::add(array(
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $qty,
                        'attributes' => array(
                            'image' => $product->main_image,
                            'code' => $product->product_code,
                            'sale_off' => $product->sale_off,
                            'shop_id' => $product->shop != null ? $product->shop->id : null,
                        )
                    ));
                }
            }else{
                if($qty < 0){
                    $old_quantity = Cart::get($id)->quantity;
                    if($old_quantity <= 1) Cart::remove($id);
                    else{
                        Cart::update($id, array(
                          'quantity' => -1,
                        ));
                    }
                }else{
                    Cart::add(array(
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $qty,
                        'attributes' => array(
                            'image' => $product->main_image,
                            'code' => $product->product_code,
                            'sale_off' => $product->sale_off,
                            'shop_id' => $product->shop != null ? $product->shop->id : null,
                        )
                    ));
                }
            }
            $carts = Cart::getContent();
        return view('front-end.cart.list',['carts'=>$carts]);
    }

    public function hoverCartAjax(Request $request){
        $carts = Cart::getContent();
        return view('front-end.partials.small-cart',['carts'=>$carts]);
    }

    public function getCart(){
        $carts = Cart::getContent();
        return view('front-end.cart.cart-detail',['carts'=>$carts]);
    }

    public function checkOut(){
        $user = auth()->user();
        if(auth()->check())
        {
            $carts = CartDb::where('payment_id', null)->where('user_id', $user->id)->get();
        }else{
            $carts = Cart::getContent();
        }
        return view('front-end.cart.check-out',['carts'=>$carts, 'user'=>$user]);
    }
    public function checkOutPost(Request $request){
        $carts = Cart::getContent();
        if(Cart::getTotalQuantity() <= 0){
            return redirect()->back();
        }
        $data = [];
        $shops = [];
        foreach($carts as $cart){
            $id = $cart->id;
            $product = Product::where('id', $id)->where('status',1)->first();
            if($product != null){
                $shop = $product->shop->where('status',1)->first();
                if($shop != null){
                    $tmp = [
                        'shop' => $shop->id,
                        'product_id' => $id,
                        'price' => $cart->price,
                        'amount'    => $cart->quantity,
                    ];
                    $total = floatval($cart->price)*intval($cart->quantity);
                    if(isset($shops[$shop->id])) $shops[$shop->id] = floatval($shops[$shop->id]) +  $total;
                    else $shops[$shop->id] = $total;
                    $data[] = $tmp;
                }
            }
        }

        $checkout = [];
        foreach ($shops as $key => $value) {
            $count = CheckOut::where('shop_id', $key)->count();
            $count = $count + 1;
            $code = 'HD-'.$key.'-'.str_pad($count, 5, '0', STR_PAD_LEFT);

            $_checkout = CheckOut::create([
                'code' => $code,
                'shop_id' => $shop->id,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'sum'     => $value,
                'status'  => 1,
            ]);
            $checkout[$_checkout->shop_id] = $_checkout->id;

            //SEND EMAIL
            $_shop = Shop::find($key);
            $email = $shop->email;
            $title = '[NÔNG SẢN THANH HÓA] Đơn hàng : '.$_checkout->code;
            $subject = '[NÔNG SẢN THANH HÓA] Đơn hàng : '.$_checkout->code;
            $content = 'Khách hàng: '.$_checkout->name. '<br>';
            $content = $content.'Số điện thoại: '.$_checkout->phone. '<br>';
            $content = $content.'Email: '.$_checkout->email. '<br>';
            $content = $content.'Địa chỉ: '.$_checkout->address. '<br>';
            $content = $content.'Thực hiện mua đơn hàng với tổng tiền là:'.number_format($_checkout->sum, 0 ,'.' ,'.').' ₫ <br>';
            send_mail($email, $title, $subject, $content);
        }

        foreach ($data as $key => $value) {
            if(isset($checkout[$value['shop']])){
                $_cart = CartDb::create([
                    'product_id' => $value['product_id'],
                    'amount' => $value['amount'],
                    'payment_id' => $checkout[$value['shop']],
                ]);
            }
        }
        Cart::clear();
        Session::flash('success-user-check-out','Bạn đã đăng kí mua hàng thành công.');
        return redirect()->back();
    }
}
