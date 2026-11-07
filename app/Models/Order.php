<?php
//Đơn hàng thuộc về một user, có nhiều chi tiết đơn hàng và lịch sử thanh toán cọc
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}

public function orderItems()
{
    return $this->hasMany(OrderItem::class, 'order_id');
}

public function payments()
{
    return $this->hasMany(Payment::class, 'order_id');
}}
