<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
//register
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:50',
            'email'    => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
            'phone'    => 'nullable|string|max:15',
            'address'  => 'nullable|string|max:500',
        ], [
            'name.required'     => 'Tên không được để trống.',
            'name.max'          => 'Tên không được vượt quá 50 ký tự.',
            'email.required'    => 'Email không được để trống.',
            'email.email'       => 'Email không đúng định dạng.',
            'email.max'         => 'Email không được vượt quá 100 ký tự.',
            'email.unique'      => 'Email này đã được tài khoản khác sử dụng.',
            'password.required' => 'Mật khẩu không được để trống.',
            'password.min'      => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'phone.max'         => 'Số điện thoại không được vượt quá 15 ký tự.',
            'address.max'       => 'Địa chỉ không được vượt quá 500 ký tự.',
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            'phone'     => $validated['phone'] ?? null,
            'address'   => $validated['address'] ?? null,
            'user_type' => 'customer',
        ]);

        return response()->json([
            'message'      => 'Đăng ký tài khoản thành công!',
            'access_token' => $user->createToken('auth_token')->plainTextToken,
            'token_type'   => 'Bearer',
            'user'         => $user
        ], 201);
    }

    
    //login( Email/Số)
     
    public function login(Request $request)
    {
        $validated = $request->validate([
            'login'    => 'required',
            'password' => 'required',
        ], [
            'login.required'    => 'Email hoặc số điện thoại không được để trống.',
            'password.required' => 'Mật khẩu không được để trống.',
        ]);

        // ? email hay số điện thoại
        $field = filter_var($validated['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::where($field, $validated['login'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Email/Số điện thoại hoặc mật khẩu không chính xác!'
            ], 401);
        }

        // Xóa token cũ và cấp token mới
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'      => 'Đăng nhập thành công!',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => [
                'id'        => $user->id,
                'name'      => $user->name,
                'email'     => $user->email,
                'phone'     => $user->phone,
                'address'   => $user->address,
                'user_type' => $user->user_type, 
            ]
        ], 200);
    }

// Xử lý quên mật khẩu / đặt lại mật khẩu mới
    public function forgotPassword(Request $request)
    {
        $validated = $request->validate([
            'login'    => 'required', // Email hoặc số điện thoại
            'password' => 'required|string|min:6',
        ], [
            'login.required'    => 'Vui lòng nhập Email hoặc Số điện thoại.',
            'password.required' => 'Mật khẩu mới không được để trống.',
            'password.min'      => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
        ]);

        // Kiểm tra xem là email hay số điện thoại
        $field = filter_var($validated['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::where($field, $validated['login'])->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email hoặc số điện thoại này chưa được đăng ký trong hệ thống!'
            ], 404);
        }

        // Cập nhật mật khẩu mới (Mã hóa Hash)
        $user->password = Hash::make($validated['password']);
        $user->save();

        // Xóa toàn bộ token cũ để bắt buộc đăng nhập lại bằng mật khẩu mới bảo mật hơn
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập lại.'
        ], 200);
    }
// logout     
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Đăng xuất thành công!'
        ], 200);
    }


    public function profile(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ], 200);
    }



    // Cập nhật thông tin cá nhân (Tên)
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
        ], [
            'name.required' => 'Tên không được để trống.',
            'name.max' => 'Tên không được vượt quá 50 ký tự.',
        ]);

        $user = $request->user();
        $user->name = $validated['name'];
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật thông tin thành công!',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'user_type' => $user->user_type,
            ]
        ], 200);
    }

    // Đổi mật khẩu người dùng
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
        ]);

        $user = $request->user();

        // Kiểm tra mật khẩu hiện tại có đúng không
        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu hiện tại không chính xác!'
            ], 422);
        }

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($validated['password']);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Đổi mật khẩu thành công!'
        ], 200);
    }
}