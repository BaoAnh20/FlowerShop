<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;

// --- 1. FE USER ---

// Trang chủ (Gọi HomeController để truyền dữ liệu categories)
Route::get('/', [HomeController::class, 'index'])->name('home');

// list sp
Route::get('/products', function () {
    return view('products.index');
})->name('products.index');

// detail sp
Route::get('/product/{id}', function ($id) {
    return view('products.show', ['id' => $id]);
})->name('products.show');

// cart
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

// Thanh toán
Route::get('/checkout', function (Request $request) {
    return view('checkout.index', ['product_id' => $request->query('product_id', 1)]);
})->name('checkout.index');

Route::post('/checkout/store', function (Request $request) {
    session(['cart_count' => 0]);
    return redirect()->route('home')->with('success', 'Cảm ơn bạn đã đặt hàng! BAFlorist sẽ liên hệ giao hoa sớm nhất.');
})->name('checkout.store');

// Quản lý đơn hàng cá nhân & Tra cứu đơn hàng
Route::get('/orders', function () {
    return view('orders.index', ['orders' => []]);
})->name('orders.index');

Route::get('/orders/lookup', function () {
    return view('orders.lookup'); // tra cứu đơn = mã đơn/email sdt
})->name('orders.lookup');

Route::get('/orders/{id}', function ($id) {
    return view('orders.show', ['id' => $id]);
})->name('orders.show');

// Xác thực tài khoản khách hàng (Login / Register)
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

// Các trang thông tin tĩnh
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
    // 1. Đăng nhập Ad
    Route::get('/login', function () {
        return view('admin.auth.login');
    })->name('login');

    Route::post('/login', function (Request $request) {
        return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập quản trị thành công!');
    })->name('login.submit');

    Route::post('/logout', function () {
        return redirect()->route('login')->with('success', 'Đã đăng xuất quản trị!');
    })->name('logout');

    // 2. Dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    });

    // 3. Quản lý danh mục
    Route::post('/categories', function (Request $request) {
        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công!');
    })->name('categories.store');

    Route::put('/categories/{id}', function (Request $request, $id) {
        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
    })->name('categories.update');

    Route::delete('/categories/{id}', function ($id) {
        return redirect()->route('admin.categories.index')->with('success', 'Đã xóa danh mục!');
    })->name('categories.destroy');

    // 4 & 5 & 6. Quản lý sản phẩm
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

    // 7 & 8. Quản lý đơn hàng & Chi tiết / Cập nhật trạng thái
    Route::get('/orders', function () {
        return view('admin.orders.index');
    })->name('orders.index');

    Route::get('/orders/{id}', function ($id) {
        return view('admin.orders.show', ['id' => $id]);
    })->name('orders.show');

    Route::patch('/orders/{id}/status', function (Request $request, $id) {
        return redirect()->back()->with('success', 'Đã cập nhật trạng thái đơn hàng!');
    })->name('orders.update_status');

    // 9. Quản lý khách hàng
    Route::get('/users', function () {
        return view('admin.users.index');
    })->name('users.index');

    // 10. Thống kê và báo cáo
    Route::get('/statistics', function () {
        return view('admin.statistics.index');
    })->name('statistics.index');
});