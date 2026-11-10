<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - BAFlorist')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Gọi file CSS via Vite -->
    @vite(['resources/css/admin.css'])
    @stack('styles')
</head>
<body style="background-color: #fcfbfa; color: #1f2937;">
    <div class="d-flex" style="min-height: 100vh;">
        <!-- Sidebar -->
        <div class="sidebar p-3 d-none d-lg-block shadow-sm" style="width: 260px; flex-shrink: 0; background: #ffffff; border-right: 1px solid rgba(123, 17, 19, 0.1);">
            <!-- Logo màu đỏ Burgundy -->
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-4 text-decoration-none px-2 gap-2">
                <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 38px; height: 38px; flex-shrink: 0; background-color: rgba(123, 17, 19, 0.1);">
                    <i class="fa-solid fa-leaf" style="color: #7b1113; font-size: 1.1rem;"></i>
                </div>
                <div class="d-flex flex-column">
                    <span class="fs-5 fw-bold lh-1" style="font-family: var(--font-heading); color: #5c0d0f;">BAFlorist</span>
                    <span class="text-uppercase text-muted" style="font-size: 9px; letter-spacing: 1.5px; font-weight: 600; margin-top: 3px;">Admin Panel</span>
                </div>
            </a>
            
            <hr class="opacity-15 mb-4" style="color: #d1c7bd;">

            <ul class="nav nav-pills flex-column mb-auto">
                <li class="mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->is('admin') ? 'active' : '' }}" style="color: #4b5563; font-weight: 600;">
                        <i class="fa-solid fa-chart-line me-2"></i> Dashboard
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}" style="color: #4b5563; font-weight: 600;">
                        <i class="fa-solid fa-spa me-2"></i> Sản phẩm & Danh mục
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}" style="color: #4b5563; font-weight: 600;">
                        <i class="fa-solid fa-receipt me-2"></i> Đơn hàng
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}" style="color: #4b5563; font-weight: 600;">
                        <i class="fa-solid fa-users me-2"></i> Tài khoản khách hàng
                    </a>
                </li>
                <li class="mb-1">
                    <a href="{{ route('admin.statistics.index') }}" class="nav-link {{ request()->is('admin/statistics*') ? 'active' : '' }}" style="color: #4b5563; font-weight: 600;">
                        <i class="fa-solid fa-chart-pie me-2"></i> Thống kê & Báo cáo
                    </a>
                </li>
            </ul>

            <hr class="opacity-15 mt-4" style="color: #d1c7bd;">

            <div class="px-2">
<form action="{{ route('admin.logout') }}" method="POST" onsubmit="localStorage.clear();">
    @csrf
    <button type="submit" class="btn btn-sm w-100 fw-semibold text-white py-2" style="background: linear-gradient(135deg, #7b1113 0%, #5c0d0f 100%); border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(123, 17, 19, 0.3);">
        <i class="fa-solid fa-right-from-bracket me-1"></i> Đăng xuất Admin
    </button>
</form>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="main-content flex-grow-1 p-4" style="width: calc(100% - 260px); overflow-x: hidden;">
            <!-- Hiển thị tiêu đề trang - lời chào -->
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary border-opacity-10">
                <div>
                    <h4 class="fw-bold mb-1" style="font-family: var(--font-heading) !important; color: #5c0d0f !important;">
                        @yield('page_title', 'Bảng Điều Khiển')
                    </h4>
                    <small class="fw-semibold text-muted" id="dynamic-greeting">
                        <!-- JavaScript sẽ tự động điền: Chào buổi [sáng/trưa/chiều/tối], Admin Bảo Anh -->
                    </small>
                </div>
            </div>

            @yield('content')
        </div>
    </div>

    <style>
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, #7b1113 0%, #5c0d0f 100%) !important;
            color: #ffffff !important;
            box-shadow: 0 6px 18px rgba(123, 17, 19, 0.25);
        }
        .sidebar .nav-link:hover:not(.active) {
            background-color: rgba(123, 17, 19, 0.06);
            color: #7b1113 !important;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Nhận diện thời gian và hiển thị tên động từ LocalStorage / API -->
    <script>
        document.addEventListener("DOMContentLoaded", async function() {
            const hour = new Date().getHours();
            let greeting = "Chào buổi tối";
            if (hour >= 4 && hour < 11) {
                greeting = "Chào buổi sáng";
            } else if (hour >= 11 && hour < 13) {
                greeting = "Chào buổi trưa";
            } else if (hour >= 13 && hour < 18) {
                greeting = "Chào buổi chiều";
            }
            
            // 1. Kiểm tra xem đã lưu tên người dùng trong localStorage lúc đăng nhập chưa
            let userName = localStorage.getItem('user_name');

            // 2. Nếu chưa có, thử gọi API lấy thông tin user hiện tại bằng token
            if (!userName) {
                try {
                    const token = localStorage.getItem('auth_token');
                    if (token) {
                        const response = await fetch('/api/user', {
                            headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
                        });
                        const user = await response.json();
                        if (user && user.name) {
                            userName = user.name;
                            localStorage.setItem('user_name', userName); // Lưu lại cho các trang sau
                        }
                    }
                } catch (err) {
                    console.error('Không thể lấy thông tin user:', err);
                }
            }

            // Nếu vẫn không tìm thấy, mặc định "Admin"
            userName = userName || "Admin BAnh";

            // Hiển thị lời chào, tên
            document.getElementById('dynamic-greeting').innerText = `${greeting}, ${userName}`;
        });
    </script>
    @stack('scripts')
</body>
</html> 