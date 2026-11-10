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


Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
/*
|--------------------------------------------------------------------------
| 1. CÁC ROUTE MỞ (PUBLIC / USER) 
|--------------------------------------------------------------------------
*/

// Xác thực tài khoản
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);



Route::post('/admin/login', [AuthController::class, 'adminLogin']);



// Danh mục (Public)
Route::get('/categories', [UserCategoryController::class, 'index']);
Route::get('/categories/{id}', [UserCategoryController::class, 'show']);

// Sản phẩm (Public)
Route::get('/products', [UserProductController::class, 'index']);
Route::get('/products/{id}', [UserProductController::class, 'show']);

// // Giỏ hàng (Guest và User)
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
    
    // Bổ sung route PUT /profile để cập nhật thông tin cá nhân 
    Route::put('/profile', [AuthController::class, 'updateProfile']);
// đổi mật khẩu
    Route::put('/profile/password', [AuthController::class, 'updatePassword']);

    // Nhóm quản trị Admin (URL: /api/admin/...) 
    Route::prefix('admin')->group(function () { 
        // Quản lý sản phẩm (Admin) 
        Route::apiResource('products', AdminProductController::class); 
        Route::delete('product-images/{id}', [AdminProductController::class, 'destroySubImage']); 
        Route::put('products/{id}/set-main-image', [AdminProductController::class, 'setMainImage']); 

        // Quản lý danh mục (Admin) 
        Route::apiResource('categories', AdminCategoryController::class); 
    }); 
});