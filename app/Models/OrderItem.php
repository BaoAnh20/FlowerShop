<?php
//Chi tiết đơn hàng thuộc về một đơn hàng và một sản phẩm cụ thể
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
public function order()
{
    return $this->belongsTo(Order::class, 'order_id');
}

public function product()
{
    return $this->belongsTo(Product::class, 'product_id');
}}
