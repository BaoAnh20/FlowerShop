@extends('layouts.app')

@section('title', 'Khôi Phục Mật Khẩu - BAFlorist')

@section('content')
<div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="glass-blur-card p-4 p-md-5 w-100 shadow-sm" style="max-width: 460px; background: rgba(255, 255, 255, 0.92); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(123, 17, 19, 0.12); border-radius: 24px;">
        
        <!-- Tiêu đề / Brand -->
        <div class="text-center mb-4">
            <h3 class="fw-bold mb-1" style="font-family: var(--font-heading); color: var(--burgundy-dark, #5c0d0f);">Quên Mật Khẩu</h3>
            <p class="text-muted small">Nhập thông tin tài khoản để thiết lập mật khẩu mới tại BAFlorist.</p>
        </div>

        <!-- Khung hiển thị lỗi khi gọi API thất bại -->
        <div id="forgotErrorAlert" class="alert alert-danger py-2 small mb-3 rounded-3 d-none" style="font-size: 0.85rem;"></div>

        <!-- Form Quên Mật Khẩu -->
        <form id="forgotPasswordForm" autocomplete="off">
            <!-- Email hoặc Số điện thoại -->
            <div class="mb-3">
                <label class="form-label fw-semibold small text-secondary">Email hoặc Số điện thoại <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0 rounded-start-pill ps-3 text-muted"><i class="fa-solid fa-user"></i></span>
                    <input type="text" id="loginInput" class="form-control rounded-end-pill px-3 py-2 bg-light border-0 shadow-none" placeholder="Nhập email hoặc số điện thoại..." autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" required>
                </div>
            </div>

            <!-- Mật khẩu mới -->
            <div class="mb-3">
                <label class="form-label fw-semibold small text-secondary">Mật khẩu mới <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0 rounded-start-pill ps-3 text-muted"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" id="newPasswordInput" class="form-control px-3 py-2 bg-light border-0 shadow-none" placeholder="Ít nhất 6 ký tự..." autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" required>
                    <button type="button" class="input-group-text bg-light border-0 rounded-end-pill pe-3 text-muted" id="togglePasswordBtn1" style="cursor: pointer;">
                        <i class="fa-regular fa-eye" id="togglePasswordIcon1"></i>
                    </button>
                </div>
            </div>

            <!-- Xác nhận mật khẩu mới -->
            <div class="mb-4">
                <label class="form-label fw-semibold small text-secondary">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0 rounded-start-pill ps-3 text-muted"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" id="confirmPasswordInput" class="form-control px-3 py-2 bg-light border-0 shadow-none" placeholder="Nhập lại mật khẩu mới..." autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" required>
                    <button type="button" class="input-group-text bg-light border-0 rounded-end-pill pe-3 text-muted" id="togglePasswordBtn2" style="cursor: pointer;">
                        <i class="fa-regular fa-eye" id="togglePasswordIcon2"></i>
                    </button>
                </div>
            </div>

            <!-- Nút Submit -->
            <button type="submit" id="submitBtn" class="btn w-100 rounded-pill py-3 fw-bold text-white shadow-sm mb-3" style="background-color: var(--burgundy-primary, #7b1113); border: none;">
                <i class="fa-solid fa-rotate-right me-1"></i> Đặt Lại Mật Khẩu
            </button>
        </form>

        <!-- Quay lại trang đăng nhập -->
        <div class="text-center mt-3 pt-3 border-top border-light">
            <a href="{{ route('login') }}" class="text-decoration-none small fw-semibold" style="color: var(--burgundy-primary, #7b1113);">
                <i class="fa-solid fa-arrow-left me-1"></i> Quay lại trang đăng nhập
            </a>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    // 1. Ẩn/hiện mật khẩu mới
    const toggleBtn1 = document.getElementById('togglePasswordBtn1');
    const newPassInput = document.getElementById('newPasswordInput');
    const toggleIcon1 = document.getElementById('togglePasswordIcon1');
    if (toggleBtn1 && newPassInput) {
        toggleBtn1.addEventListener('click', function () {
            const isPassword = newPassInput.type === 'password';
            newPassInput.type = isPassword ? 'text' : 'password';
            toggleIcon1.className = isPassword ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
        });
    }

    // 2. Ẩn/hiện xác nhận mật khẩu
    const toggleBtn2 = document.getElementById('togglePasswordBtn2');
    const confirmPassInput = document.getElementById('confirmPasswordInput');
    const toggleIcon2 = document.getElementById('togglePasswordIcon2');
    if (toggleBtn2 && confirmPassInput) {
        toggleBtn2.addEventListener('click', function () {
            const isPassword = confirmPassInput.type === 'password';
            confirmPassInput.type = isPassword ? 'text' : 'password';
            toggleIcon2.className = isPassword ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
        });
    }

    // 3. Xử lý gọi API đặt lại mật khẩu
    document.getElementById('forgotPasswordForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const loginVal = document.getElementById('loginInput').value.trim();
        const passwordVal = newPassInput.value;
        const confirmVal = confirmPassInput.value;
        const errorAlert = document.getElementById('forgotErrorAlert');
        const submitBtn = document.getElementById('submitBtn');

        errorAlert.classList.add('d-none');
        errorAlert.innerText = '';

        if (passwordVal !== confirmVal) {
            errorAlert.innerText = 'Mật khẩu xác nhận không khớp. Vui lòng kiểm tra lại!';
            errorAlert.classList.remove('d-none');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerText = 'Đang xử lý...';

        try {
            const response = await fetch('/api/forgot-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ login: loginVal, password: passwordVal })
            });

            const result = await response.json();

            if (response.ok) {
                alert(result.message || 'Đặt lại mật khẩu thành công!');
                window.location.href = '/login';
            } else {
                errorAlert.innerText = result.message || 'Không thể đặt lại mật khẩu. Vui lòng kiểm tra lại thông tin.';
                errorAlert.classList.remove('d-none');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa-solid fa-rotate-right me-1"></i> Đặt Lại Mật Khẩu';
            }
        } catch (err) {
            console.error(err);
            errorAlert.innerText = 'Lỗi kết nối đến máy chủ.';
            errorAlert.classList.remove('d-none');
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-rotate-right me-1"></i> Đặt Lại Mật Khẩu';
        }
    });
</script>
@endpush