<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart'; // Khớp tên bảng trong CSDL PostgreSQL

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity'
    ];

    // Quan hệ với User (Giỏ hàng thuộc về 1 user nếu đã đăng nhập)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Quan hệ với Product (Mỗi mục giỏ hàng ứng với 1 sản phẩm)
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}