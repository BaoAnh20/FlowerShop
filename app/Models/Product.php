<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'name',
        'image',
        'details',      
        'price',
        'stock_quantity',
        'is_active',
        'style',        
        'primary_flower', 
        'occasion'       
    ];

    // Quan hệ với danh mục chính
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Quan hệ với chi tiết đơn hàng
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    // Quan hệ với giỏ hàng
    public function cartItems()
    {
        return $this->hasMany(Cart::class, 'product_id');
    }
    
    // Quan hệ với hình ảnh chi tiết sản phẩm
    public function images() {
    return $this->hasMany(ProductImage::class, 'product_id');
}
}