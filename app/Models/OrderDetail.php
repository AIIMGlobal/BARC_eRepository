<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function orderInfo()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function userInfo()
    {
        return $this->belongsTo(Farmer::class,'farmer_id','id');
    }

    public function productInfo()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }
}
