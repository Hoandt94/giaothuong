@extends('front-end.layouts.main')

@section('css')
 
@endsection

@section('title')
Giỏ hàng - Kết nối cung cầu nông sản Hoằng Hóa
@endsection

@section('content')

<div class="wrap-page">
    <div class="register-bg">
        <div class="block-title-h1" style="background-image: url({{asset('front-end/images/kt-banner.jpg')}})">
            <div class="container"><h1>Giỏ hàng</h1></div>
        </div>
        <div class="container" id="contentCart">
            @include('front-end.cart.list')
        </div>
    </div>
</div>
@include('front-end.modals.check-out')
@endsection

@section('js')
<script type="text/javascript">
    var url_add_cart = $('#base_url').val() + 'change-cart/ajax';
    $("#contentCart" ).delegate(".check_out", "click", function() {
       $('#modal-info').modal('toggle');
    });

    $("#contentCart" ).delegate(".cong", "click", function() {
        id = $(this).data('id');
        $.get(url_add_cart, { product_id: id, num: 1}, function (data) {
            $('#contentCart').empty().html(data);
            $('.cart-count').html($('#sum_cart').val());
        });
    })
    $("#contentCart" ).delegate(".tru", "click", function() {
        id = $(this).data('id');
        $.get(url_add_cart, { product_id: id, num: -1}, function (data) {
            $('#contentCart').empty().html(data);
            $('.cart-count').html($('#sum_cart').val());
        });
    })
   $("#contentCart" ).delegate(".qty", "change", function() {
        id = $(this).data('id');
        qty = $(this).val();
        $.get(url_add_cart, { product_id: id, num: qty, type: 'change'}, function (data) {
            $('#contentCart').empty().html(data);
            $('.cart-count').html($('#sum_cart').val());
        });
    });
    $("#contentCart" ).delegate(".btn-remove-cart-item", "click", function() {
        id = $(this).data('id');
        $.get(url_add_cart, { product_id: id, num: -1, type: 'del'}, function (data) {
            $('#contentCart').empty().html(data);
            $('.cart-count').html($('#sum_cart').val());
        });
    })
</script>
@endsection