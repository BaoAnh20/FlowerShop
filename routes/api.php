<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Sử dụng các Controller phía User / Public
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\User\CategoryController as UserCategoryController;
use App\Http\Controllers\Api\User\ProductController as UserProductController;
use App\Http\Controllers\Api\CartController;

// Sử dụng các Controller phía Admin
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;

/*
|--------------------------------------------------------------------------
| 1. CÁC ROUTE MỞ (PUBLIC / USER) - Không yêu cầu đăng nhập
|--------------------------------------------------------------------------
*/

// Xác thực tài khoản
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Danh mục (Public)
Route::get('/categories', [UserCategoryController::class, 'index']);
Route::get('/categories/{id}', [UserCategoryController::class, 'show']);

// Sản phẩm (Public - Không yêu cầu đăng nhập)
Route::get('/products', [UserProductController::class, 'index']);
Route::get('/products/{id}', [UserProductController::class, 'show']);

// Giỏ hàng (Hỗ trợ cả Guest và User)
Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart', [CartController::class, 'store']);
Route::put('/cart/{id}', [CartController::class, 'update']);
Route::delete('/cart/{id}', [CartController::class, 'destroy']);


/*
|--------------------------------------------------------------------------
| 2. CÁC ROUTE CẦN XÁC THỰC & QUẢN TRỊ (AUTHENTICATED & ADMIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth cá nhân
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    // Nhóm quản trị Admin (Tiền tố URL: /api/admin/...)
    Route::prefix('admin')->group(function () {
        
        // Quản lý sản phẩm (Admin)
        Route::apiResource('products', AdminProductController::class);
        Route::delete('product-images/{id}', [AdminProductController::class, 'destroySubImage']);
        Route::put('products/{id}/set-main-image', [AdminProductController::class, 'setMainImage']);

        // Quản lý danh mục (Admin - Dùng apiResource để có đủ GET, POST, PUT, DELETE, tránh lỗi 405)
        Route::apiResource('categories', AdminCategoryController::class);
    });
});