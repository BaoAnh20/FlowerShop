<nav class="navbar shadow-sm sticky-top py-2">
    <div class="container d-flex align-items-center justify-content-between flex-wrap gap-2">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center gap-2 m-0 fw-bold fs-4 text-decoration-none" href="{{ route('home') }}">
            <i class="fa-solid fa-leaf text-primary-custom"></i> BAFlorist
        </a>

        <!-- Danh sách Menu -->
        <ul class="nav d-flex align-items-center gap-1 gap-md-2 mb-0">
            <li class="nav-item">
                <a class="nav-link text-nowrap {{ request()->routeIs('home') ? 'active fw-bold' : '' }}" href="{{ route('home') }}">
                    Trang chủ
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-nowrap {{ request()->is('products*') ? 'active fw-bold' : '' }}" href="/products">
                    Sản phẩm
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-nowrap {{ request()->routeIs('orders.*') ? 'active fw-bold' : '' }}" href="{{ route('orders.index') }}">
                    Đơn hàng
                </a>
            </li>

            <!-- Danh mục Dropdown (Động 100% từ bảng categories) -->
            <li class="nav-item dropdown dropdown-hover">
                <a class="nav-link text-nowrap dropdown-toggle d-flex align-items-center gap-1" href="#" id="categoryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Danh mục <i class="fa-solid fa-chevron-down ms-1" style="font-size: 0.7rem;"></i>
                </a>
                <ul class="dropdown-menu border-0 shadow-lg rounded-4 p-2 glass-dropdown" aria-labelledby="categoryDropdown" style="max-height: 380px; overflow-y: auto;">
                    @forelse($categories ?? [] as $category)
                        <li>
                            <a class="dropdown-item rounded-3 py-2 px-3 d-flex align-items-center gap-2" href="/products?category_id={{ $category->id }}">
                                <i class="fa-solid fa-spa text-primary-custom"></i> {{ $category->name }}
                            </a>
                        </li>
                    @empty
                        <li><span class="dropdown-item text-muted small">Chưa có danh mục nào</span></li>
                    @endforelse
                    <li><hr class="dropdown-divider opacity-25 my-1"></li>
                    <li>
                        <a class="dropdown-item rounded-3 py-2 px-3 fw-bold d-flex align-items-center justify-content-between text-primary-custom" href="/products">
                            <span>Tất cả các loại hoa</span>
                            <i class="fa-solid fa-arrow-right small"></i>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- TÌM KIẾM -->
        <div class="d-flex align-items-center gap-2 my-auto flex-grow-1 flex-md-grow-0" style="min-width: 250px; max-width: 360px;">
            <form action="/products" method="GET" class="position-relative w-100">
                <input type="text" name="search" class="form-control form-control-sm rounded-pill ps-3 pe-5 border-0 bg-white shadow-sm" placeholder="Tìm sản phẩm hoa, dịp tặng, cảm xúc..." value="{{ request('search') }}" style="height: 40px; font-size: 0.875rem; border: 1px solid rgba(123, 17, 19, 0.15) !important;">
                <button type="submit" class="btn btn-link position-absolute end-0 top-50 translate-middle-y me-2 p-0 border-0 text-primary-custom" title="Tìm kiếm">
                    <i class="fa-solid fa-magnifying-glass fs-6"></i>
                </button>
            </form>
        </div>

        <!-- phải -->
        <div class="d-flex align-items-center gap-2 gap-md-3">
            <!-- Giỏ hàng -->
            <button class="btn rounded-circle position-relative p-2 d-flex align-items-center justify-content-center shadow-sm bg-white text-primary-custom" type="button" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-controls="cartOffcanvas" style="width: 40px; height: 40px; border: 1px solid rgba(123, 17, 19, 0.25) !important;">
                <i class="fa-solid fa-bag-shopping"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary-custom text-white" style="font-size: 0.65rem;">
                    {{ $cartCount ?? 0 }}
                </span>
            </button>

            <!-- Tài khoản / User Icon -->
            <div class="user-dropdown-wrapper dropdown" id="userAuthContainer">
                <!-- 1. Trạng thái CHƯA ĐĂNG NHẬP -->
                <div id="guestSection">
                    <a href="{{ route('login') }}" class="user-icon-trigger text-decoration-none" aria-label="Tài khoản">
                        <i class="fa-regular fa-user"></i>
                    </a>
                    <div class="user-hover-menu shadow-lg">
                        <a href="{{ route('login') }}" class="dropdown-item text-decoration-none d-block">
                            <i class="fa-solid fa-arrow-right-to-bracket me-2"></i>Đăng nhập
                        </a>
                        <a href="{{ route('register') }}" class="dropdown-item text-decoration-none d-block">
                            <i class="fa-solid fa-user-plus me-2"></i>Đăng ký
                        </a>
                    </div>
                </div>

                <!-- 2. Trạng thái ĐÃ ĐĂNG NHẬP -->
                <div id="loggedSection" class="d-none">
                    <a href="#" class="user-icon-trigger text-decoration-none d-flex align-items-center justify-content-center p-0" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="width: 42px; height: 42px; border-radius: 50%; background: #ffffff; border: 1px solid rgba(123, 17, 19, 0.25); color: var(--burgundy-primary);">
                        <span class="fw-bold d-flex align-items-center justify-content-center m-0" id="userAvatarText" style="width: 100%; height: 100%; font-size: 1rem; line-height: 0;">U</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 py-2" aria-labelledby="userDropdown" style="min-width: 210px;">
                        <li>
                            <div class="px-3 py-2 text-muted small border-bottom mb-1">
                                <span class="d-block text-dark fw-bold text-truncate" id="userNameDisplay" style="max-width: 180px;">Tên người dùng</span>
                                <span class="text-secondary text-truncate d-block" id="userEmailDisplay" style="font-size: 0.75rem; max-width: 180px;">email@example.com</span>
                            </div>
                        </li>
                        <li id="adminDashboardItem" class="d-none">
                            <a class="dropdown-item py-2 small fw-semibold text-dark" href="/admin/dashboard">
                                <i class="fa-solid fa-gauge-high me-2 text-muted"></i>Quản trị Admin
                            </a>
                        </li>
                        <li><a class="dropdown-item py-2 small fw-semibold text-dark" href="/profile"><i class="fa-solid fa-user-gear me-2 text-muted"></i>Hồ sơ cá nhân</a></li>
                        <li><a class="dropdown-item py-2 small fw-semibold text-dark" href="/orders"><i class="fa-solid fa-bag-shopping me-2 text-muted"></i>Đơn hàng của tôi</a></li>
                        <li><hr class="dropdown-divider my-1 opacity-25"></li>
                        <li><button class="dropdown-item py-2 small fw-bold text-danger bg-transparent border-0 w-100 text-start" id="navLogoutBtn"><i class="fa-solid fa-right-from-bracket me-2"></i>Đăng xuất</button></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- KHUNG GIỎ HÀNG (OFFCANVAS) - DỮ LIỆU ĐỘNG -->
<div class="offcanvas offcanvas-end border-0 shadow-lg" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel" style="width: 420px; background-color: var(--bg-pastel);">
    <div class="offcanvas-header border-bottom py-3 px-4" style="border-color: rgba(123, 17, 19, 0.1) !important;">
        @php 
            $miniCartItems = isset($cartItems) ? $cartItems : []; 
            $cartTotal = collect($miniCartItems)->sum(function($item) {
                return ($item->product->price ?? $item->price ?? 0) * $item->quantity;
            });
        @endphp
        <h5 class="offcanvas-title fw-bold fs-5 d-flex align-items-center gap-2 text-primary-custom" id="cartOffcanvasLabel">
            <i class="fa-solid fa-bag-shopping text-primary-custom"></i> Giỏ hàng ({{ count($miniCartItems) }})
        </h5>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body px-4 py-3 d-flex flex-column">
        @if(count($miniCartItems) > 0)
            <div class="flex-grow-1 overflow-y-auto">
                @foreach($miniCartItems as $item)
                    @php
                        $pId = $item->product->id ?? $item->product_id ?? '#';
                        $pName = $item->product->name ?? $item->name ?? 'Sản phẩm';
                        $pPrice = $item->product->price ?? $item->price ?? 0;
                        $pImage = $item->product->image ?? $item->image ?? '';
                        // Chuẩn hóa đường dẫn ảnh từ storage nếu cần
if (!$pImage) {
        $imgUrl = 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?q=80&w=300';
    } elseif (str_starts_with($pImage, 'http')) {
        $imgUrl = $pImage;
    } elseif (str_starts_with($pImage, 'storage/')) {
        $imgUrl = '/' . $pImage;
    } elseif (str_starts_with($pImage, 'images/')) {
        $imgUrl = '/storage/' . $pImage;
    } else {
        $imgUrl = '/storage/images/' . $pImage;
    }                    @endphp
                    <div class="d-flex align-items-center justify-content-between pb-3 mb-3 border-bottom position-relative" style="border-color: rgba(123, 17, 19, 0.08) !important;">
                        <div class="d-flex align-items-center gap-3">
                            <!-- Ảnh sản phẩm bấm vào chuyển đến trang chi tiết -->
                            <a href="/product/{{ $pId }}">
                                <img src="{{ $imgUrl }}" class="rounded-3 object-fit-cover shadow-sm border" width="70" height="70" alt="{{ $pName }}" onerror="this.src='https://images.unsplash.com/photo-1561181286-d3fee7d55364?q=80&w=300'">
                            </a>
                            <div>
                                <!-- Thay đổi tên sản phẩm thành thẻ a dẫn đến trang chi tiết sản phẩm -->
                                <h6 class="mb-1" style="font-size: 0.9rem; line-height: 1.3;">
                                    <a href="/product/{{ $pId }}" class="text-decoration-none text-dark fw-bold hover-burgundy">
                                        {{ $pName }}
                                    </a>
                                </h6>
                                <div class="fw-bold price-number mb-2 text-primary-custom" style="font-size: 0.92rem;">
                                    {{ number_format($pPrice, 0, ',', '.') }} đ
                                </div>
                                <div class="input-group input-group-sm rounded-0" style="width: 105px;">
                                    <span class="form-control text-center p-0 bg-white border-secondary-subtle shadow-none fw-bold rounded-0" style="font-size: 0.85rem;">SL: {{ $item->quantity }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-end d-flex flex-column align-items-end justify-content-between flex-shrink-0" style="height: 65px; min-width: 90px;">
                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-muted p-0 border-0 shadow-none hover-danger lh-1" style="font-size: 0.85rem;" title="Xóa">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </form>
                            <span class="fw-bold price-number text-dark text-nowrap" style="font-size: 0.88rem;">
                                {{ number_format($pPrice * $item->quantity, 0, ',', '.') }} đ
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pt-3 border-top mt-auto" style="border-color: rgba(123, 17, 19, 0.1) !important;">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-secondary fw-medium">Tổng cộng</span>
                    <span class="fw-bold fs-4 price-number text-primary-custom">
                        {{ number_format($cartTotal, 0, ',', '.') }} đ
                    </span>
                </div>
                <div class="d-grid gap-2">
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-custom rounded-pill py-2.5 fw-semibold text-dark bg-white shadow-sm text-decoration-none text-center">
                        <i class="fa-solid fa-list-check me-1 text-primary-custom"></i> Xem chi tiết giỏ hàng
                    </a>
                    <a href="{{ route('cart.index') }}" class="btn btn-primary-custom w-100 rounded-pill py-3 fw-bold text-uppercase shadow-sm text-decoration-none text-center" style="letter-spacing: 0.5px;">
                        Thanh toán <i class="fa-solid fa-arrow-right-long ms-2"></i>
                    </a>
                </div>
            </div>
        @else
            <div class="text-center py-5 my-auto">
                <i class="fa-solid fa-basket-shopping fs-1 text-muted mb-3 text-primary-custom"></i>
                <h6 class="fw-bold text-secondary">Giỏ hàng trống</h6>
                <p class="text-muted small">Hãy chọn thêm các mẫu hoa tươi tuyệt đẹp nhé.</p>
<a href="/products" class="btn btn-primary-custom rounded-pill px-4 mt-2 text-decoration-none">
    <i class="fa-solid fa-spa me-1"></i> Khám phá hoa tươi
</a>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const token = localStorage.getItem('auth_token');
        const userInfoStr = localStorage.getItem('user_info');
        const guestSection = document.getElementById('guestSection');
        const loggedSection = document.getElementById('loggedSection');

        if (token && userInfoStr) {
            try {
                const user = JSON.parse(userInfoStr);
                if (guestSection) guestSection.classList.add('d-none');
                if (loggedSection) loggedSection.classList.remove('d-none');

                const avatarText = document.getElementById('userAvatarText');
                const nameDisplay = document.getElementById('userNameDisplay');
                const emailDisplay = document.getElementById('userEmailDisplay');
                const adminItem = document.getElementById('adminDashboardItem');

                if (avatarText && user.name) {
                    avatarText.innerText = user.name.charAt(0).toUpperCase();
                }
                if (nameDisplay) nameDisplay.innerText = user.name || 'Khách hàng';
                if (emailDisplay) emailDisplay.innerText = user.email || user.phone || '';

                if (adminItem && user.user_type === 'admin') {
                    adminItem.classList.remove('d-none');
                }

                const logoutBtn = document.getElementById('navLogoutBtn');
                if (logoutBtn) {
                    logoutBtn.addEventListener('click', async function () {
                        try {
                            await fetch('/api/logout', {
                                method: 'POST',
                                headers: {
                                    'Authorization': 'Bearer ' + token,
                                    'Accept': 'application/json'
                                }
                            });
                        } catch (err) {
                            console.error('Lỗi khi gọi API logout:', err);
                        }
                        localStorage.removeItem('auth_token');
                        localStorage.removeItem('user_info');
                        localStorage.clear();
                        alert('Đăng xuất thành công!');
                        window.location.href = '/';
                    });
                }
            } catch (e) {
                console.error('Lỗi đọc thông tin user:', e);
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user_info');
            }
        }
    });
</script>