<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Cart;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Tự động truyền dữ liệu categories, cartItems và cartCount cho TẤT CẢ các View
        View::composer('*', function ($view) {
            // 1. Lấy danh mục đang hoạt động
            $categories = Category::where('is_active', true)->get();

            // 2. Lấy giỏ hàng theo User (nếu đã login) hoặc Session (khách vãng lai)
            if (Auth::check()) {
                $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
            } else {
                $sessionId = session()->get('cart_session_id');
                $cartItems = $sessionId ? Cart::where('session_id', $sessionId)->with('product')->get() : collect();
            }

            // 3. Chia sẻ toàn bộ biến sang View
            $view->with([
                'categories' => $categories,
                'cartItems'  => $cartItems,
                'cartCount'  => $cartItems->sum('quantity') // Tổng số lượng hoa trong giỏ
            ]);
        });
    }
}