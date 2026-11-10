@extends('layouts.app')

@section('title', 'Danh Sách Sản Phẩm - BAFlorist')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="mb-4">
        <div class="text-center mb-4">
            <h1 class="fw-normal text-dark fs-2 mb-0" style="font-family: var(--font-heading);" id="pageTitle">
                Tất cả sản phẩm hoa
            </h1>
        </div>

        <div class="d-flex align-items-center justify-content-between pt-2 border-bottom pb-3 position-relative">
            <span class="text-secondary small fw-normal" id="productCountText">
                Đang tải số lượng sản phẩm...
            </span>

            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-link text-dark text-decoration-none p-0 d-flex align-items-center gap-2 small fw-normal shadow-none" type="button" onclick="toggleFilterDropdown(event)">
                    <i class="fa-solid fa-sliders fs-6 text-primary-custom"></i> Lọc
                </button>
                <span class="text-black-50">|</span>
                <div class="dropdown">
                    <button class="btn btn-link text-dark text-decoration-none p-0 d-flex align-items-center gap-2 small fw-normal dropdown-toggle shadow-none" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-arrow-down-up-between fs-6 text-primary-custom"></i>
                        <span id="sortButtonLabel">Đề xuất</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 p-2 glass-dropdown" aria-labelledby="sortDropdown">
                        <li><a class="dropdown-item rounded-2 py-2 small sort-option" href="#" data-sort="latest" data-label="Mới nhất">Mới nhất</a></li>
                        <li><a class="dropdown-item rounded-2 py-2 small sort-option" href="#" data-sort="price_asc" data-label="Giá: Thấp đến Cao">Giá: Thấp đến Cao</a></li>
                        <li><a class="dropdown-item rounded-2 py-2 small sort-option" href="#" data-sort="price_desc" data-label="Giá: Cao đến Thấp">Giá: Cao đến Thấp</a></li>
                        <li><a class="dropdown-item rounded-2 py-2 small sort-option" href="#" data-sort="popular" data-label="Bán chạy nhất">Bán chạy nhất</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bộ Lọc -->
            <div id="filterDropdownPanel" class="shadow-lg rounded-4 bg-white border p-4" style="display: none; position: absolute; right: 0; top: 45px; width: 460px; z-index: 1050; border-color: rgba(123, 17, 19, 0.12) !important;">
                <form id="filterForm">
                    <div class="row g-4 text-start">
                        <!-- giá -->
                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                                <i class="fa-solid fa-tag text-primary-custom small"></i>
                                <h6 class="fw-bold mb-0 fs-6 text-primary-custom" style="font-family: var(--font-heading);">Mức giá</h6>
                            </div>
                            <div class="d-flex flex-column gap-2.5">
                                @foreach(['under_500' => 'Dưới 500k', '500_1000' => '500k - 1 triệu', '1000_1500' => '1tr - 1.5 triệu', '1500_2000' => '1.5tr - 2 triệu', '2000_3000' => '2tr - 3 triệu', 'above_3000' => 'Trên 3 triệu'] as $key => $label)
                                    <div class="form-check custom-checkbox-wrapper mb-1">
                                        <input class="form-check-input custom-checkbox price-range-checkbox" type="checkbox" value="{{ $key }}" id="price-{{ $key }}">
                                        <label class="form-check-label text-secondary small cursor-pointer" for="price-{{ $key }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Danh mục -->
                        <div class="col-6">
                            <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                                <i class="fa-solid fa-spa text-primary-custom small"></i>
                                <h6 class="fw-bold mb-0 fs-6 text-primary-custom" style="font-family: var(--font-heading);">Danh mục</h6>
                            </div>
                            <div class="d-flex flex-column gap-2.5">
                                @php
                                    $categories = [
                                        1 => 'Hoa Sinh Nhật',
                                        2 => 'Hoa Khai Trương',
                                        3 => 'Hoa Hồng',
                                        4 => 'Hộp Hoa Mica',
                                        5 => 'Hoa Tang Lễ'
                                    ];
                                @endphp
                                @foreach($categories as $catId => $catName)
                                    <div class="form-check custom-checkbox-wrapper mb-1">
                                        <input class="form-check-input custom-checkbox category-checkbox" type="checkbox" value="{{ $catId }}" id="cat-{{ $catId }}">
                                        <label class="form-check-label text-secondary small cursor-pointer" for="cat-{{ $catId }}">{{ $catName }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mt-4 pt-3 border-top">
                        <button type="button" class="btn btn-link text-muted text-decoration-none small hover-burgundy p-0 shadow-none border-0 bg-transparent" onclick="resetFilters()">
                            <i class="fa-solid fa-rotate-left me-1"></i> Xóa bộ lọc
                        </button>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1.5 small" onclick="closeFilterDropdown()">Đóng</button>
                            <button type="submit" class="btn btn-primary-custom btn-sm rounded-pill px-4 py-1.5 small fw-semibold">Áp dụng</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Khu vực hiển thị sản phẩm phân theo nhóm danh mục -->
    <div id="productsContainer">
        <div class="text-center py-5">
            <i class="fa-solid fa-spinner fa-spin fs-2 text-primary-custom mb-3"></i>
            <p class="text-muted small">Đang tải danh sách hoa tươi...</p>
        </div>
    </div>
</div>

@push('styles')
<style>
    .custom-checkbox {
        width: 1.15rem;
        height: 1.15rem;
        margin-top: 0.1rem;
        border-color: #d1b5b6;
        cursor: pointer;
    }
    .custom-checkbox:checked {
        background-color: var(--burgundy-primary, #7b1113);
        border-color: var(--burgundy-primary, #7b1113);
    }
    .custom-checkbox:focus {
        box-shadow: 0 0 0 0.25rem rgba(123, 17, 19, 0.15);
        border-color: var(--burgundy-primary, #7b1113);
    }
    .cursor-pointer { cursor: pointer; }
    .hover-burgundy:hover { color: var(--burgundy-primary, #7b1113) !important; }
</style>
@endpush

@push('scripts')
<script>
let currentSort = '';

document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const searchKeyword = urlParams.get('search') || '';
    const categoryId = urlParams.get('category_id') || '';

    let initialParams = {};

    if (searchKeyword) {
        document.getElementById('pageTitle').innerText = `Kết quả tìm kiếm cho "${searchKeyword}"`;
        initialParams.search = searchKeyword;
    }

    // 1. Tự động nhận diện category_id từ URL khi bấm từ Navbar sang
    if (categoryId) {
        initialParams.category_id = categoryId;
        
        // Tự động tích chọn vào ô checkbox danh mục tương ứng trong bảng lọc
        const targetCheckbox = document.getElementById(`cat-${categoryId}`);
        if (targetCheckbox) {
            targetCheckbox.checked = true;
        }
    }

    // 2. Gọi API tải sản phẩm dựa trên tham số ban đầu (search hoặc category_id)
    fetchProducts(initialParams);

    // Sự kiện submit form bộ lọc
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        loadFilteredAndSortedProducts();
        closeFilterDropdown();
    });

    // Xử lý sự kiện sắp xếp
    document.querySelectorAll('.sort-option').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            currentSort = this.getAttribute('data-sort');
            const labelText = this.getAttribute('data-label');
            document.getElementById('sortButtonLabel').innerText = labelText;
            loadFilteredAndSortedProducts();
        });
    });
});

function loadFilteredAndSortedProducts() {
    const urlParams = new URLSearchParams(window.location.search);
    const searchKeyword = urlParams.get('search') || '';
    const selectedCategories = Array.from(document.querySelectorAll('.category-checkbox:checked')).map(el => el.value);
    const selectedPrices = Array.from(document.querySelectorAll('.price-range-checkbox:checked')).map(el => el.value);

    let params = {};
    if (searchKeyword) params.search = searchKeyword;
    if (selectedCategories.length > 0) params.category_id = selectedCategories.join(',');
    if (selectedPrices.length > 0) params.price_ranges = selectedPrices.join(',');
    if (currentSort) params.sort = currentSort;

    fetchProducts(params);
}

// Hàm chuẩn hóa đường dẫn ảnh 
function getFormattedClientImgUrl(imgSrc) {
    if (!imgSrc) return 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?q=80&w=600';
    if (imgSrc.startsWith('http')) return imgSrc;
    if (imgSrc.startsWith('storage/')) return '/' + imgSrc;
    if (!imgSrc.startsWith('images/')) return '/storage/images/' + imgSrc;
    return '/storage/' + imgSrc;
}

async function fetchProducts(params = {}) {
    const container = document.getElementById('productsContainer');
    const countText = document.getElementById('productCountText');
    
    container.innerHTML = `
        <div class="text-center py-5"> 
            <i class="fa-solid fa-spinner fa-spin fs-2 text-primary-custom mb-3"></i> 
            <p class="text-muted small">Đang tải danh sách hoa tươi...</p> 
        </div>
    `; 

    try {
        const result = await window.apiService.getProducts(params); 
        if (result.ok && result.data && result.data.success) { 
            // Bóc tách chính xác mảng sản phẩm từ cấu trúc phân trang Laravel (result.data.data.data)
            const paginatedObj = result.data.data; 
            const products = Array.isArray(paginatedObj) ? paginatedObj : (paginatedObj.data || []); 
            
            countText.innerText = `${products.length} sản phẩm`; 

            if (products.length > 0) { 
                // Gom nhóm sản phẩm theo tên danh mục 
                const grouped = {}; 
                products.forEach(product => { 
                    const catName = product.category ? product.category.name : 'Danh mục khác'; 
                    if (!grouped[catName]) { 
                        grouped[catName] = []; 
                    } 
                    grouped[catName].push(product); 
                }); 

                let html = ''; 
                for (const [catName, catProducts] of Object.entries(grouped)) { 
                    html += ` 
                        <div class="mb-5"> 
                            <h3 class="fw-bold mb-3 text-dark fs-4" style="font-family: var(--font-heading);"> ${catName} </h3> 
                            <div class="row g-3"> 
                    `; 

                    catProducts.forEach(product => { 
                        let imgSrc = getFormattedClientImgUrl(product.image); 
                        let formattedPrice = Number(product.price || 0).toLocaleString('vi-VN') + ' đ'; 
                        
                        html += ` 
                            <div class="col-6 col-sm-4 col-md-3 col-lg-2"> 
                                <div class="card h-100 border-0 shadow-sm rounded-3 overflow-hidden product-card"> 
                                    <a href="/product/${product.id}" class="text-decoration-none text-dark"> 
                                        <div style="height: 160px; overflow: hidden;">
                                            <img src="${imgSrc}" class="w-100 h-100 object-fit-cover" alt="${product.name}" onerror="this.src='https://images.unsplash.com/photo-1561181286-d3fee7d55364?q=80&w=600'"> 
                                        </div>
                                    </a> 
                                    <div class="card-body p-2 text-center d-flex flex-column justify-content-between"> 
                                        <div> 
                                            <a href="/product/${product.id}" class="text-decoration-none text-dark fw-semibold small line-clamp-2" style="font-size: 0.85rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"> 
                                                ${product.name}
                                            </a> 
                                        </div> 
                                        <div class="text-primary-custom fw-bold mt-2" style="font-size: 0.88rem;"> ${formattedPrice} </div> 
                                    </div> 
                                </div> 
                            </div> 
                        `; 
                    }); 

                    html += ` 
                            </div> 
                        </div> 
                    `; 
                } 
                container.innerHTML = html; 
            } else { 
                container.innerHTML = ` 
                    <div class="text-center py-5"> 
                        <i class="fa-solid fa-spa fs-1 text-primary-custom mb-3 opacity-50"></i> 
                        <p class="text-secondary mb-0">Không tìm thấy mẫu hoa nào phù hợp với lựa chọn của bạn!</p> 
                    </div> 
                `; 
                countText.innerText = `0 sản phẩm`; 
            } 
        } else { 
            container.innerHTML = '<div class="text-center text-danger py-4">Không thể tải dữ liệu sản phẩm từ máy chủ.</div>'; 
        } 
    } catch (error) { 
        console.error('Lỗi khi gọi API sản phẩm:', error); 
        container.innerHTML = '<div class="text-center text-danger py-4">Lỗi kết nối đến máy chủ.</div>'; 
    } 
}

function toggleFilterDropdown(event) {
    event.stopPropagation();
    const panel = document.getElementById('filterDropdownPanel');
    panel.style.display = (panel.style.display === 'none' || panel.style.display === '') ? 'block' : 'none';
}

function closeFilterDropdown() {
    document.getElementById('filterDropdownPanel').style.display = 'none';
}

function resetFilters() {
    document.querySelectorAll('.category-checkbox, .price-range-checkbox').forEach(el => el.checked = false);
    currentSort = '';
    document.getElementById('sortButtonLabel').innerText = 'Đề xuất';
    loadFilteredAndSortedProducts();
    closeFilterDropdown();
}

window.addEventListener('click', function(e) {
    const panel = document.getElementById('filterDropdownPanel');
    const filterBtn = document.querySelector('[onclick="toggleFilterDropdown(event)"]');
    if (panel && filterBtn) {
        if (!panel.contains(e.target) && !filterBtn.contains(e.target)) {
            panel.style.display = 'none';
        }
    }
});
</script>
@endpush
@endsection