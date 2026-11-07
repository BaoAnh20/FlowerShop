<?php
//Giao dịch thanh toán gắn với một đơn hàng
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
public function order()
{
    return $this->belongsTo(Order::class, 'order_id');
}

}
