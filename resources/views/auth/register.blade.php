@extends('layouts.app') 

@section('title', 'Đăng Ký Tài Khoản - BAFlorist') 

@section('content') 
<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 80vh;"> 
    <div class="glass-blur-card p-4 p-md-5 w-100 shadow-sm" style="max-width: 480px; background: rgba(255, 255, 255, 0.92); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(123, 17, 19, 0.12); border-radius: 24px;"> 
        
        <div class="text-center mb-4"> 
            <h3 class="fw-bold mb-1" style="font-family: var(--font-heading); color: var(--burgundy-dark, #5c0d0f);">Tạo Tài Khoản</h3> 
            <p class="text-muted small">Đăng ký thành viên để tích điểm và nhận ưu đãi độc quyền từ BAFlorist.</p> 
        </div> 

        <!-- notice lỗi khi gọi API -->
        <div id="registerErrorAlert" class="alert alert-danger py-2 small mb-3 rounded-3 d-none" style="font-size: 0.85rem;"></div>

        <!-- Form Đăng Ký  -->
        <form id="registerForm"> 
            <!-- Họ và tên --> 
            <div class="mb-3"> 
                <label class="form-label fw-semibold small text-secondary">Họ và tên đầy đủ <span class="text-danger">*</span></label> 
                <div class="input-group"> 
                    <span class="input-group-text bg-light border-0 rounded-start-pill ps-3 text-muted"><i class="fa-regular fa-user"></i></span> 
                    <input type="text" id="nameInput" class="form-control rounded-end-pill px-3 py-2 bg-light border-0 shadow-none" placeholder="Nhập họ và tên của bạn" required> 
                </div> 
            </div> 

            <!-- Email --> 
            <div class="mb-3"> 
                <label class="form-label fw-semibold small text-secondary">Địa chỉ Email <span class="text-danger">*</span></label> 
                <div class="input-group"> 
                    <span class="input-group-text bg-light border-0 rounded-start-pill ps-3 text-muted"><i class="fa-solid fa-envelope"></i></span> 
                    <input type="email" id="emailInput" class="form-control rounded-end-pill px-3 py-2 bg-light border-0 shadow-none" placeholder="Nhập địa chỉ email" required> 
                </div> 
            </div> 

            <!-- Số điện thoại (Tùy chọn CÓ/KHÔNG đều được) --> 
            <div class="mb-3"> 
                <label class="form-label fw-semibold small text-secondary">Số điện thoại</label> 
                <div class="input-group"> 
                    <span class="input-group-text bg-light border-0 rounded-start-pill ps-3 text-muted"><i class="fa-solid fa-phone"></i></span> 
                    <input type="text" id="phoneInput" class="form-control rounded-end-pill px-3 py-2 bg-light border-0 shadow-none" placeholder="Nhập số điện thoại"> 
                </div> 
            </div> 

            <!-- Mật khẩu --> 
            <div class="mb-3"> 
                <label class="form-label fw-semibold small text-secondary">Mật khẩu <span class="text-danger">*</span></label> 
                <div class="input-group"> 
                    <span class="input-group-text bg-light border-0 rounded-start-pill ps-3 text-muted"><i class="fa-solid fa-lock"></i></span> 
                    <input type="password" id="passwordInput" class="form-control px-3 py-2 bg-light border-0 shadow-none" placeholder="Ít nhất 6 ký tự" required> 
                    <button type="button" class="input-group-text bg-light border-0 rounded-end-pill pe-3 text-muted" id="togglePasswordBtn" style="cursor: pointer;"> 
                        <i class="fa-regular fa-eye" id="togglePasswordIcon"></i> 
                    </button> 
                </div> 
            </div> 

            <button type="submit" id="submitBtn" class="btn btn-primary-custom w-100 rounded-pill py-3 fw-bold text-white shadow-sm mb-3" style="background-color: var(--burgundy-primary, #7b1113); border: none;"> 
                Đăng Ký <i class="fa-solid fa-arrow-right-long ms-2"></i> 
            </button> 
        </form> 

        <!-- Điều hướng sang đăng nhập --> 
        <div class="text-center mt-3 pt-3 border-top border-light"> 
            <small class="text-muted">Đã có tài khoản?</small> 
            <a href="{{ route('login') }}" class="text-decoration-none fw-bold ms-1" style="color: var(--burgundy-primary, #7b1113);">Đăng nhập ngay</a> 
        </div> 
    </div> 
</div> 
@endsection 

@push('scripts') 
<script>
    // 1.  ẩn/hiện mật khẩu
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


    document.getElementById('registerForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const nameVal = document.getElementById('nameInput').value;
        const emailVal = document.getElementById('emailInput').value;
        const phoneVal = document.getElementById('phoneInput').value;
        const passwordVal = passwordInput.value;
        
        const errorAlert = document.getElementById('registerErrorAlert');
        const submitBtn = document.getElementById('submitBtn');

        errorAlert.classList.add('d-none');
        submitBtn.disabled = true;
        submitBtn.innerText = 'Đang xử lý...';

        // Gọi API register qua service chung
        const result = await window.apiService.register({
            name: nameVal,
            email: emailVal,
            phone: phoneVal,
            password: passwordVal
        });

        if (result.ok) {
            // Đăng ký thành công -> Tự động lưu Token và chuyển về trang chủ
            localStorage.setItem('auth_token', result.data.access_token);
            localStorage.setItem('user_info', JSON.stringify(result.data.user));

            alert('Đăng ký tài khoản thành công!');
            window.location.href = '/';
        } else {
            // Hiển thị lỗi trả về từ API
            let errorMsg = result.data.message || 'Đăng ký thất bại!';
            if (result.data.errors) {
                const firstKey = Object.keys(result.data.errors)[0];
                errorMsg = result.data.errors[firstKey][0];
            }

            errorAlert.innerText = errorMsg;
            errorAlert.classList.remove('d-none');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Đăng Ký <i class="fa-solid fa-arrow-right-long ms-2"></i>';
        }
    });
</script> 
@endpush