@extends('layouts.app')

@section('title', 'Tài Khoản Của Tôi - BAFlorist')

@section('content')
<div class="container py-5" style="max-width: 800px;">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
            <li class="breadcrumb-item active fw-bold text-primary-custom" aria-current="page">Tài khoản của tôi</li>
        </ol>
    </nav>

    <div class="text-center mb-4">
        <h2 class="fw-bold" style="font-family: 'Playfair Display', serif; color: #5c0d0f;">Hồ Sơ Cá Nhân</h2>
        <p class="text-muted small">Quản lý thông tin tài khoản và bảo mật bảo vệ đơn hàng của bạn tại BAFlorist.</p>
    </div>

    <!-- Khung thông báo lỗi / thành công động chung -->
    <div id="profileAlert" class="alert d-none border-0 rounded-4 shadow-sm p-3 mb-4"></div>

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm p-3 mb-4">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        <!-- Cập nhật thông tin -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white" style="border: 1px solid rgba(123, 17, 19, 0.1) !important;">
                <h5 class="fw-bold mb-3 pb-2 border-bottom" style="font-family: 'Playfair Display', serif; color: #5c0d0f;">
                    <i class="fa-regular fa-user me-2"></i> Thông Tin Cá Nhân
                </h5>
                <form id="updateProfileForm">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" id="profileName" name="name" class="form-control rounded-pill px-3 py-2 bg-light border-0 shadow-none" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Địa chỉ Email đăng nhập</label>
                        <input type="email" id="profileEmail" class="form-control rounded-pill px-3 py-2 bg-light border-0 shadow-none text-muted" disabled>
                        <small class="text-muted" style="font-size: 0.72rem;">Email dùng để định danh tài khoản và không thể thay đổi.</small>
                    </div>

                    <button type="submit" id="updateProfileBtn" class="btn btn-primary-custom w-100 rounded-pill py-2 fw-bold text-white shadow-sm mt-3">
                        Cập nhật thông tin
                    </button>
                </form>
            </div>
        </div>

        <!-- Cập nhật mật khẩu -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white" style="border: 1px solid rgba(123, 17, 19, 0.1) !important;">
                <h5 class="fw-bold mb-3 pb-2 border-bottom" style="font-family: 'Playfair Display', serif; color: #5c0d0f;">
                    <i class="fa-solid fa-lock me-2"></i> Đổi Mật Khẩu
                </h5>
                <form id="updatePasswordForm">
                    <!-- Mật khẩu hiện tại -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" id="currentPassword" class="form-control px-3 py-2 bg-light border-0 shadow-none rounded-start-pill" placeholder="••••••••" required>
                            <button type="button" class="input-group-text bg-light border-0 rounded-end-pill pe-3 text-muted" id="togglePass1" style="cursor: pointer;">
                                <i class="fa-regular fa-eye" id="iconPass1"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Mật khẩu mới -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Mật khẩu mới <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" id="newPassword" class="form-control px-3 py-2 bg-light border-0 shadow-none rounded-start-pill" placeholder="Ít nhất 6 ký tự" required>
                            <button type="button" class="input-group-text bg-light border-0 rounded-end-pill pe-3 text-muted" id="togglePass2" style="cursor: pointer;">
                                <i class="fa-regular fa-eye" id="iconPass2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Xác nhận mật khẩu mới -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-secondary">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" id="confirmPassword" class="form-control px-3 py-2 bg-light border-0 shadow-none rounded-start-pill" placeholder="Nhập lại mật khẩu mới" required>
                            <button type="button" class="input-group-text bg-light border-0 rounded-end-pill pe-3 text-muted" id="togglePass3" style="cursor: pointer;">
                                <i class="fa-regular fa-eye" id="iconPass3"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" id="updatePasswordBtn" class="btn btn-outline-burgundy w-100 rounded-pill py-2 fw-semibold mt-3">
                        Đổi mật khẩu
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Hàm hỗ trợ ẩn/hiện mật khẩu
    function setupTogglePassword(toggleBtnId, inputId, iconId) {
        const btn = document.getElementById(toggleBtnId);
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (btn && input && icon) {
            btn.addEventListener('click', function () {
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                icon.className = isPassword ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
            });
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        const token = localStorage.getItem('auth_token');
        const userInfoStr = localStorage.getItem('user_info');
        const alertBox = document.getElementById('profileAlert');

        // Kích hoạt tính năng ấn xem mật khẩu cho 3 ô
        setupTogglePassword('togglePass1', 'currentPassword', 'iconPass1');
        setupTogglePassword('togglePass2', 'newPassword', 'iconPass2');
        setupTogglePassword('togglePass3', 'confirmPassword', 'iconPass3');

        // 1. Tự động điền dữ liệu thật từ localStorage vào form
        if (userInfoStr) {
            try {
                const user = JSON.parse(userInfoStr);
                if (document.getElementById('profileName')) {
                    document.getElementById('profileName').value = user.name || 'Test';
                }
                if (document.getElementById('profileEmail')) {
                    document.getElementById('profileEmail').value = user.email || 'test@gmail.com';
                }
            } catch (e) {
                console.error('Lỗi đọc user_info:', e);
            }
        } else {
            if (document.getElementById('profileName')) document.getElementById('profileName').value = 'Test';
            if (document.getElementById('profileEmail')) document.getElementById('profileEmail').value = 'test@gmail.com';
        }

        // 2. Xử lý submit form Cập nhật thông tin cá nhân qua API
        const profileForm = document.getElementById('updateProfileForm');
        if (profileForm) {
            profileForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const nameVal = document.getElementById('profileName').value;
                
                try {
                    const response = await fetch('/api/profile', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        },
                        body: JSON.stringify({ name: nameVal })
                    });
                    
                    const result = await response.json();
                    if (response.ok) {
                        let user = JSON.parse(localStorage.getItem('user_info') || '{}');
                        user.name = nameVal;
                        localStorage.setItem('user_info', JSON.stringify(user));

                        alertBox.className = 'alert alert-success border-0 rounded-4 shadow-sm p-3 mb-4';
                        alertBox.innerHTML = '<i class="fa-solid fa-circle-check me-2"></i> Cập nhật thông tin thành công!';
                        alertBox.classList.remove('d-none');
                    } else {
                        alertBox.className = 'alert alert-danger border-0 rounded-4 shadow-sm p-3 mb-4';
                        alertBox.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-2"></i> ' + (result.message || 'Có lỗi xảy ra.');
                        alertBox.classList.remove('d-none');
                    }
                } catch (err) {
                    console.error(err);
                    alert('Lỗi kết nối máy chủ.');
                }
            });
        }

        // 3. Xử lý submit form Đổi Mật Khẩu qua API
        const passwordForm = document.getElementById('updatePasswordForm');
        if (passwordForm) {
            passwordForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                const current_password = document.getElementById('currentPassword').value;
                const password = document.getElementById('newPassword').value;
                const password_confirmation = document.getElementById('confirmPassword').value;

                if (password !== password_confirmation) {
                    alertBox.className = 'alert alert-danger border-0 rounded-4 shadow-sm p-3 mb-4';
                    alertBox.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-2"></i> Mật khẩu xác nhận không khớp!';
                    alertBox.classList.remove('d-none');
                    return;
                }

                try {
                    const response = await fetch('/api/profile/password', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'Authorization': 'Bearer ' + token
                        },
                        body: JSON.stringify({
                            current_password: current_password,
                            password: password,
                            password_confirmation: password_confirmation
                        })
                    });

                    const result = await response.json();
                    if (response.ok) {
                        alertBox.className = 'alert alert-success border-0 rounded-4 shadow-sm p-3 mb-4';
                        alertBox.innerHTML = '<i class="fa-solid fa-circle-check me-2"></i> ' + (result.message || 'Đổi mật khẩu thành công!');
                        alertBox.classList.remove('d-none');
                        passwordForm.reset();
                    } else {
                        alertBox.className = 'alert alert-danger border-0 rounded-4 shadow-sm p-3 mb-4';
                        alertBox.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-2"></i> ' + (result.message || 'Có lỗi xảy ra.');
                        alertBox.classList.remove('d-none');
                    }
                } catch (err) {
                    console.error(err);
                    alert('Lỗi kết nối máy chủ.');
                }
            });
        }
    });
</script>
@endpush