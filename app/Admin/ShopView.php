<?php

namespace App\Admin;

use Illuminate\Database\Eloquent\Model;

class ShopView extends Model
{
    protected $fillable = [
        'shop_id', 'ip_address', 'user_id', 
    ];

    public function product()
    {
        return $this->belongsTo('App\shop','shop_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','product_id');
    }
}
