<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;

class CheckOut extends Model
{
    protected $fillable = [
        'shop_id', 'code', 'name', 'phone', 'email', 'address', 'sum', 'status',
    ];

    public function cart_db()
    {
        return $this->hasMany('App\Admin\CartDb', 'payment_id');
    }
    public function shop()
    {
        return $this->belongsTo('App\Shop', 'shop_id');
    }

}
