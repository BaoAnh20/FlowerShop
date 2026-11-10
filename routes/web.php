<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;

// --- 1. FE USER ---
Route::get('/', [HomeController::class, 'index'])->name('home');

// Quên mật khẩu {{ route('password.request') }} 4 trang Login
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

// Danh sách sản phẩm (User)
Route::get('/products', function () {
    return view('products.index');
})->name('products.index');

Route::get('/product/{id}', function ($id) {
    return view('products.show', ['id' => $id]);
})->name('products.show');



// --- xử lý Hồ sơ cá nhân (Profile) ---
Route::get('/profile', function () {
    return view('profile.index');
})->name('profile.index');

Route::put('/profile', function (Request $request) {
    // Xử lý cập nhật thông tin cá nhân (Tên,...)
    return redirect()->route('profile.index')->with('success', 'Cập nhật thông tin cá nhân thành công!');
})->name('profile.update');

Route::put('/profile/password', function (Request $request) {
    // Xử lý đổi mật khẩu
    return redirect()->route('profile.index')->with('success', 'Đổi mật khẩu thành công!');
})->name('profile.password.update');





Route::get('/cart', function () {
    return view('cart.index', ['cartItems' => []]);
})->name('cart.index');

Route::post('/cart/add/{id?}', function (Request $request, $id = null) {
    $count = session('cart_count', 0) + ($request->input('quantity', 1));
    session(['cart_count' => $count]);
    return redirect()->back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
})->name('cart.add');

Route::patch('/cart/update/{id}', function (Request $request, $id) {
    return redirect()->back()->with('success', 'Đã cập nhật số lượng!');
})->name('cart.update');

Route::delete('/cart/remove/{id}', function ($id) {
    $count = max(0, session('cart_count', 1) - 1);
    session(['cart_count' => $count]);
    return redirect()->back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
})->name('cart.remove');

Route::get('/checkout', function (Request $request) {
    return view('checkout.index', ['product_id' => $request->query('product_id', 1)]);
})->name('checkout.index');

Route::post('/checkout/store', function (Request $request) {
    session(['cart_count' => 0]);
    return redirect()->route('home')->with('success', 'Cảm ơn bạn đã đặt hàng! BAFlorist sẽ liên hệ giao hoa sớm nhất.');
})->name('checkout.store');

Route::get('/orders', function () {
    return view('orders.index', ['orders' => []]);
})->name('orders.index');

Route::get('/orders/lookup', function () {
    return view('orders.lookup'); 
})->name('orders.lookup');

Route::get('/orders/{id}', function ($id) {
    return view('orders.show', ['id' => $id]);
})->name('orders.show');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
})->name('login.store');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', function (Request $request) {
    return redirect()->route('home')->with('success', 'Tạo tài khoản thành công!');
})->name('register.store');

Route::post('/logout', function () {
    return redirect()->route('home')->with('success', 'Đã đăng xuất!');
})->name('logout');

Route::get('/ve-baflorist', function () {
    return view('aboutus.index');
})->name('aboutus.index');

Route::get('/chinh-sach-giao-hang', function () {
    return view('shipprivacy.index');
})->name('shipprivacy.index');

Route::get('/chinh-sach-doi-tra', function () {
    return view('refund.index');
})->name('refund.index');


// --- 2. FE ADMIN ---
Route::prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', function (Request $request) {
        return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập quản trị thành công!');
    })->name('login.submit');

    Route::post('/logout', function () {
        return redirect()->route('admin.login')->with('success', 'Đã đăng xuất quản trị!');
    })->name('logout');

    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });

    Route::get('/categories', function () {
        return view('admin.categories.index');
    })->name('categories.index');

    Route::post('/categories', function (Request $request) {
        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công!');
    })->name('categories.store');

    Route::put('/categories/{id}', function (Request $request, $id) {
        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
    })->name('categories.update');

    Route::delete('/categories/{id}', function ($id) {
        return redirect()->route('admin.categories.index')->with('success', 'Đã xóa danh mục!');
    })->name('categories.destroy');

    // QUẢN LÝ SẢN PHẨM ADMIN
    Route::get('/products', function () {
        return view('admin.products.index');
    })->name('products.index');

    Route::post('/products', function (Request $request) {
        return redirect()->route('admin.products.index')->with('success', 'Thêm mẫu hoa thành công!');
    })->name('products.store');

    Route::put('/products/{id}', function (Request $request, $id) {
        return redirect()->route('admin.products.index')->with('success', 'Cập nhật mẫu hoa thành công!');
    })->name('products.update');

    Route::delete('/products/{id}', function ($id) {
        return redirect()->route('admin.products.index')->with('success', 'Đã xóa mẫu hoa!');
    })->name('products.destroy');

    Route::get('/orders', function () {
        return view('admin.orders.index');
    })->name('orders.index');

    Route::get('/orders/{id}', function ($id) {
        return view('admin.orders.show', ['id' => $id]);
    })->name('orders.show');

    Route::patch('/orders/{id}/status', function (Request $request, $id) {
        return redirect()->back()->with('success', 'Đã cập nhật trạng thái đơn hàng!');
    })->name('orders.update_status');

    Route::get('/users', function () {
        return view('admin.users.index');
    })->name('users.index');

    Route::get('/statistics', function () {
        return view('admin.statistics.index');
    })->name('statistics.index');
});