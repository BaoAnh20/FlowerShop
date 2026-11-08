@extends('layouts.app') 

@section('title', 'Đăng Nhập Tài Khoản - BAFlorist') 

@section('content') 
<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 75vh;"> 
    <div class="glass-blur-card p-4 p-md-5 w-100 shadow-sm" style="max-width: 460px; background: rgba(255, 255, 255, 0.92); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(123, 17, 19, 0.12); border-radius: 24px;"> 
        <div class="text-center mb-4"> 
            <h3 class="fw-bold mb-1" style="font-family: var(--font-heading); color: var(--burgundy-dark, #5c0d0f);">Đăng Nhập</h3> 
            <p class="text-muted small">Chào mừng bạn quay trở lại với tiệm hoa BAFlorist.</p> 
        </div> 

        <!-- Lỗi khi gọi API thất bại --> 
        <div id="loginErrorAlert" class="alert alert-danger py-2 small mb-3 rounded-3 d-none" style="font-size: 0.85rem;"></div> 

        <!-- Thêm autocomplete="off" để chặn trình duyệt gợi ý -->
        <form id="loginForm" autocomplete="off"> 
            <!-- Email - Số điện thoại --> 
            <div class="mb-3"> 
                <label class="form-label fw-semibold small text-secondary">Email hoặc Số điện thoại <span class="text-danger">*</span></label> 
                <div class="input-group"> 
                    <span class="input-group-text bg-light border-0 rounded-start-pill ps-3 text-muted"><i class="fa-solid fa-user-shield"></i></span> 
                    <!-- Sử dụng autocomplete="nope" hoặc readonly trick để chặn trình duyệt autofill -->
                    <input type="text" name="login" id="loginInput" class="form-control rounded-end-pill px-3 py-2 bg-light border-0 shadow-none" placeholder="Nhập email hoặc số điện thoại" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" required> 
                </div> 
            </div> 

            <!-- Mật khẩu --> 
            <div class="mb-3"> 
                <div class="d-flex justify-content-between align-items-center"> 
                    <label class="form-label fw-semibold small text-secondary">Mật khẩu <span class="text-danger">*</span></label> 
                    <a href="#" class="text-decoration-none small" style="color: var(--burgundy-primary, #7b1113);">Quên mật khẩu?</a> 
                </div> 
                <div class="input-group"> 
                    <span class="input-group-text bg-light border-0 rounded-start-pill ps-3 text-muted"><i class="fa-solid fa-lock"></i></span> 
                    <input type="password" name="password" id="passwordInput" class="form-control px-3 py-2 bg-light border-0 shadow-none" placeholder="••••••••" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" required> 
                    <button type="button" class="input-group-text bg-light border-0 rounded-end-pill pe-3 text-muted" id="togglePasswordBtn" style="cursor: pointer;"> 
                        <i class="fa-regular fa-eye" id="togglePasswordIcon"></i> 
                    </button> 
                </div> 
            </div> 

            <div class="form-check mb-4"> 
                <input class="form-check-input custom-checkbox" type="checkbox" name="remember" id="remember" style="border-color: #7b1113;"> 
                <label class="form-check-label small text-muted cursor-pointer" for="remember"> 
                    Ghi nhớ đăng nhập trên thiết bị này 
                </label> 
            </div> 

            <button type="submit" id="submitBtn" class="btn btn-primary-custom w-100 rounded-pill py-3 fw-bold text-white shadow-sm mb-3" style="background-color: var(--burgundy-primary, #7b1113); border: none;"> 
                Đăng Nhập <i class="fa-solid fa-arrow-right-long ms-2"></i> 
            </button> 
        </form> 

        <!-- Điều hướng sang đăng ký --> 
        <div class="text-center mt-3 pt-3 border-top border-light"> 
            <small class="text-muted">Bạn chưa có tài khoản tại BAFlorist?</small> 
            <a href="{{ route('register') }}" class="text-decoration-none fw-bold ms-1" style="color: var(--burgundy-primary, #7b1113);">Đăng ký ngay</a> 
        </div> 
    </div> 
</div> 
@endsection 

@push('scripts') 
<script> 
// 1. Ẩn/hiện mật khẩu 
const toggleBtn = document.getElementById('togglePasswordBtn'); 
const passwordInput = document.getElementById('passwordInput'); 
const toggleIcon = document.getElementById('togglePasswordIcon'); 

if (toggleBtn && passwordInput) { 
    toggleBtn.addEventListener('click', function () { 
        const isPassword = passwordInput.type === 'password'; 
        passwordInput.type = isPassword ? 'text' : 'password'; 
        toggleIcon.className = isPassword ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'; 
    }); 
} 

// 2. Kết nối API đăng nhập qua window.apiService 
document.getElementById('loginForm').addEventListener('submit', async function(e) { 
    e.preventDefault(); 
    const loginVal = document.getElementById('loginInput').value.trim(); 
    const passwordVal = passwordInput.value; 
    const errorAlert = document.getElementById('loginErrorAlert'); 
    const submitBtn = document.getElementById('submitBtn'); 

    errorAlert.classList.add('d-none'); 
    errorAlert.innerText = ''; 
    submitBtn.disabled = true; 
    submitBtn.innerText = 'Đang xử lý...'; 

    // Gọi qua file apiService 
    const result = await window.apiService.login({ 
        login: loginVal, 
        password: passwordVal 
    }); 

    if (result.ok) { 
        // Lưu token và thông tin người dùng vào localStorage 
        localStorage.setItem('auth_token', result.data.access_token); 
        localStorage.setItem('user_info', JSON.stringify(result.data.user)); 

        // Phân quyền điều hướng 
        if (result.data.user.user_type === 'admin') { 
            window.location.href = '/admin/dashboard'; 
        } else { 
            window.location.href = '/'; 
        } 
    } else { 
        // Hiển thị lỗi trả về từ API Backend 
        errorAlert.innerText = result.data.message || 'Đăng nhập thất bại, vui lòng thử lại!'; 
        errorAlert.classList.remove('d-none'); 
        submitBtn.disabled = false; 
        submitBtn.innerHTML = 'Đăng Nhập <i class="fa-solid fa-arrow-right-long ms-2"></i>'; 
    } 
}); 
</script> 
@endpush