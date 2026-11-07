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
}