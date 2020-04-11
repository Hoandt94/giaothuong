<div class="cart-page-shop-section__items">
    @php
        $shopIds = [];
        foreach(\Cart::getContent() as $cart){
            $cart_att_shop = $cart->attributes->get('shop_id');
            if(!isset($shopIds[$cart_att_shop]) || $shopIds[$cart_att_shop] == null){
                $shopIds[$cart_att_shop] = 1;
            }else{
                $shopIds[$cart_att_shop] = $shopIds[$cart_att_shop] + 1;
            }
        }
    @endphp
    @foreach($shopIds as $key=>$value)
        @php
            $i = 1;
            $shop = App\Shop::find($key);
        @endphp
        @if($shop != null)
            @foreach(\Cart::getContent() as $cart)
                @if($cart->attributes->get('shop_id') == $key)
                    @php
                        $product = App\Admin\Product::find($cart->id);
                    @endphp
                    @if($product != null)
                        @if($i==1)
                        <div class="cart-page-shop-header">
                            <a class="cart-page-shop-header__shop-name" href="#">
                                <span style="margin-left: 10px;">{{$shop->tencs}}</span>
                            </a>
                        </div>
                        @endif
                        @php
                        $i ++;
                        @endphp
                        <div class="cart-item">
                            <div class="cart-item__head cart-item__cell-overview">
                                <a href="{{route('product.detail',['slug'=>$product->slug])}}" class="img-cart"><img src="{{get_image_product($product->product_code.'/'.$product->main_image)}}"></a>
                                <div class="cart-item-overview__product-name-wrapper"><a href="{{route('product.detail',['slug'=>$product->slug])}}">{{$product->name}}</a></div>
                            </div>
                            <div class="cart-item__head cart-item__cell-unit-price">
                                @if($product->sale_off <= 0)
                                @php
                                $off = $product->price;
                                @endphp
                                <span class="cart-item__unit-price cart-item__unit-price--after">{{number_format($product->price, 0 ,'.' ,'.')}} ₫ </span>
                                @else
                                @php
                                $off = $product->sale_off;
                                @endphp
                                <span class="cart-item__unit-price cart-item__unit-price--after">{{number_format($product->sale_off, 0 ,'.' ,'.')}} ₫ </span>
                                @endif
                                <span class="cart-item__unit-price cart-item__unit-price--before">{{number_format($product->price, 0 ,'.' ,'.')}} ₫</span>
                            </div>
                            <div class="cart-item__head cart-item__cell-quantity">
                                <span class="item-quantity-prefix"></span>
                                <button class="reduced items-count tru" data-id="{{$product->id}}" type="button">
                                    <i class="icon-minus"> - </i>
                                </button>
                                <input type="text" name="quantity" data-id="{{$product->id}}" value="{{$cart->quantity}}" size="2"  class="item-quantity-value qty" maxlength="12">

                                <button class="increase items-count cong" data-id="{{$product->id}}" type="button">
                                    <span> + </span>
                                </button>
                            </div>
                            <div class="cart-item__head  cart-item__cell-total-unit473" data-id="{{$product->id}}">{{$product->unit}}</div>
                            <div class="cart-item__head  cart-item__cell-total-price473" data-id="{{$product->id}}">{{number_format($off*$cart->quantity, 0 ,'.' ,'.')}} ₫</div>
                            <div class="cart-item__head cart-item__cell-actions">
                                <button data-id="{{$product->id}}" class="cart-item__action btn-remove-cart-item">Xóa</button>
                            </div>
                        </div>
                    @endif
                @endif
            @endforeach
        @endif
    @endforeach
</div>