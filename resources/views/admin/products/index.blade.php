@extends('layouts.admin')

@section('title', 'Quản Lý Sản Phẩm & Danh Mục - BAFlorist Admin')
@section('page_title', 'Quản Lý Danh Mục & Mẫu Hoa')

@section('content')
<!-- TAB CHUYỂN ĐỔI SẢN PHẨM - DANH MỤC -->
<ul class="nav nav-pills mb-4 gap-2" id="adminTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active rounded-pill px-4 fw-bold shadow-sm" id="products-tab" data-bs-toggle="pill" data-bs-target="#products-pane" type="button" role="tab" aria-controls="products-pane" aria-selected="true" style="background-color: #7b1113; color: #ffffff; border: 1px solid #7b1113;">
            <i class="fa-solid fa-spa me-2"></i> Quản Lý Mẫu Hoa
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link rounded-pill px-4 fw-bold shadow-sm" id="categories-tab" data-bs-toggle="pill" data-bs-target="#categories-pane" type="button" role="tab" aria-controls="categories-pane" aria-selected="false" style="background-color: #ffffff; color: #5c0d0f; border: 1px solid #d1c7bd;">
            <i class="fa-solid fa-list me-2"></i> Quản Lý Danh Mục
        </button>
    </li>
</ul>

<div class="tab-content" id="adminTabContent">

<!-- SP -->
<div class="tab-pane fade show active" id="products-pane" role="tabpanel" aria-labelledby="products-tab">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-3 mb-4">
            <div class="search-box position-relative" style="max-width: 350px; width: 100%;">
                <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
                    <i class="fa-solid fa-search"></i>
                </span>
                <input type="text" id="productSearchInput" class="form-control rounded-pill ps-5 pe-4 py-2 border shadow-sm text-dark bg-white" placeholder="Tìm kiếm tên mẫu hoa..." style="border-color: #e5e2de !important;">
            </div>
            <button type="button" class="btn btn-primary rounded-pill px-4 py-2 text-white fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" data-bs-toggle="modal" data-bs-target="#productModal" onclick="resetProductForm()" style="background-color: #7b1113; border: none;">
                <i class="fa-solid fa-plus-circle fs-5" style="color: #ffffff !important;"></i>
                <span style="color: #ffffff !important;">Thêm Mẫu Hoa Mới</span>
            </button>
        </div>

        <div class="card border-0 shadow-sm bg-white p-3" style="border-radius: 16px;">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead>
                        <tr class="text-uppercase fs-7 border-bottom" style="background-color: #f9f8f6;">
                            <th style="width: 110px; color: #5c0d0f !important;" class="py-3">Hình Ảnh</th>
                            <th class="py-3" style="color: #5c0d0f !important;">Tên Sản Phẩm</th>
                            <th class="py-3" style="color: #5c0d0f !important;">Danh Mục</th>
                            <th class="py-3" style="color: #5c0d0f !important;">Giá Bán</th>
                            <th class="text-end py-3" style="width: 190px; color: #5c0d0f !important;">Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody">
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Đang tải danh sách sản phẩm...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>



<!-- DANH MỤC -->

    <div class="tab-pane fade" id="categories-pane" role="tabpanel" aria-labelledby="categories-tab">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <p class="small mb-0 text-muted">Quản lý các nhóm hoa tươi (Hoa Sinh Nhật, Khai Trương, Tình Yêu...)</p>
            <button type="button" class="btn btn-sm btn-primary px-4 py-2 fw-bold shadow-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#categoryModal" onclick="resetCategoryForm()">
                <i class="fa-solid fa-plus me-1"></i> Thêm Danh Mục Mới
            </button>
        </div>

        <div class="card border-0 shadow-sm bg-white p-3" style="border-radius: 16px;">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr class="small border-bottom" style="background-color: #f9f8f6;">
                            <th style="width: 90px; color: #5c0d0f !important; padding: 14px 12px;">HÌNH ẢNH</th>
                            <th style="width: 80px; color: #5c0d0f !important; padding: 14px 12px;">STT</th>
                            <th style="color: #5c0d0f !important; padding: 14px 12px;">TÊN DANH MỤC</th>
                            <th style="color: #5c0d0f !important; padding: 14px 12px;">MÔ TẢ</th>
                            <th class="text-end" style="width: 180px; color: #5c0d0f !important; padding: 14px 12px;">THAO TÁC</th>
                        </tr>
                    </thead>
                    <tbody id="categoriesTableBody">
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Đang tải danh mục...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL SP-->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded-4 border-0 p-4 shadow-lg bg-white">
            <div class="modal-header border-0 pb-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background-color: rgba(123, 17, 19, 0.1);">
                        <i class="fa-solid fa-spa" style="color: #7b1113;"></i>
                    </div>
                    <h5 class="modal-title fw-bold mb-0 text-dark" id="prodModalTitle">Thêm Mẫu Hoa Mới</h5>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="productApiForm">
                <input type="hidden" id="prodId">
                <div class="modal-body pt-3">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Tên mẫu hoa <span class="text-danger">*</span></label>
                        <input type="text" id="prodName" class="form-control rounded-pill px-4 py-2.5 bg-light border-0 shadow-none text-dark" placeholder="Nhập tên sản phẩm hoa..." required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Danh mục <span class="text-danger">*</span></label>
                            <select id="prodCat" class="form-select rounded-pill px-4 py-2.5 bg-light border-0 shadow-none text-dark" required>
                                <option value="">-- Chọn danh mục --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-dark">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" id="prodPrice" class="form-control rounded-pill px-4 py-2.5 bg-light border-0 shadow-none text-dark" placeholder="Ví dụ: 7900000" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Chọn Ảnh Chính (Thumbnail)</label>
                        <input type="file" id="prodImageFile" class="form-control rounded-pill px-4 py-2 bg-light border-0 shadow-none text-dark" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Chọn Các Ảnh Phụ (Có thể chọn nhiều ảnh cùng lúc)</label>
                        <input type="file" id="prodSubImagesFiles" class="form-control rounded-pill px-4 py-2 bg-light border-0 shadow-none text-dark" accept="image/*" multiple>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-dark">Mô tả chi tiết sản phẩm</label>
                        <textarea id="prodDesc" class="form-control rounded-4 p-3 bg-light border-0 shadow-none text-dark" rows="3" placeholder="Nhập thành phần hoa, ý nghĩa, thông điệp..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-3">
                    <button type="button" class="btn btn-light rounded-pill px-4 py-2 fw-semibold text-secondary shadow-sm" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 text-white fw-bold shadow-sm" style="background-color: #7b1113;">Lưu Thay Đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL DM -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 p-3 shadow-lg bg-white">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark" id="catModalTitle">Thêm Danh Mục Mới</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="categoryApiForm">
                <input type="hidden" id="catId">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" id="catName" class="form-control px-3 py-2.5 bg-light border-0 shadow-none text-dark rounded-pill" placeholder="Nhập tên danh mục hoa" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Ảnh đại diện danh mục</label>
                        <input type="file" id="catImageFile" class="form-control rounded-pill px-3 py-2 bg-light border-0 shadow-none text-dark" accept="image/*">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-semibold text-dark">Mô tả ngắn</label>
                        <textarea id="catDesc" class="form-control p-3 bg-light border-0 shadow-none text-dark rounded-4" rows="3" placeholder="Nhập mô tả cho danh mục..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-4 rounded-pill shadow-sm" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4 fw-bold rounded-pill shadow-sm" style="background-color: #7b1113;">Lưu Thay Đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .hover-scale { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-scale:hover { transform: translateY(-2px); }
    .table-hover tbody tr:hover { background-color: rgba(123, 17, 19, 0.03) !important; }
    .nav-pills .nav-link.active { background-color: #7b1113 !important; color: #ffffff !important; border-color: #7b1113 !important; }
    .nav-pills .nav-link:not(.active) { background-color: #ffffff !important; color: #5c0d0f !important; border: 1px solid #d1c7bd !important; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    loadAdminProducts();
    loadAdminCategories();
    loadCategoryDropdown();

    // 1. Submit Form Thêm / Sửa Sản Phẩm 
    document.getElementById('productApiForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('prodId').value;
        const formData = new FormData();
        formData.append('name', document.getElementById('prodName').value);
        formData.append('category_id', document.getElementById('prodCat').value);
        formData.append('price', document.getElementById('prodPrice').value);
        formData.append('details', document.getElementById('prodDesc').value);
        formData.append('stock_quantity', 100);
        formData.append('is_active', 1);

        const mainImageFile = document.getElementById('prodImageFile').files[0];
        if (mainImageFile) formData.append('image', mainImageFile);

        const subImageFiles = document.getElementById('prodSubImagesFiles').files;
        for (let i = 0; i < subImageFiles.length; i++) {
            formData.append('images[]', subImageFiles[i]);
        }

        if (id) formData.append('_method', 'PUT');

        const token = localStorage.getItem('auth_token');
        const url = id ? `/api/products/${id}` : '/api/products';

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    ...(token ? { 'Authorization': `Bearer ${token}` } : {})
                },
                body: formData
            });

            const result = await response.json();
            if (response.ok && result.success) {
                alert(id ? 'Cập nhật sản phẩm thành công!' : 'Thêm sản phẩm thành công!');
                bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
                loadAdminProducts();
            } else {
                alert('Lỗi: ' + (result.message || JSON.stringify(result.errors || 'Không thể lưu sản phẩm.')));
            }
        } catch (err) {
            console.error(err);
            alert('Lỗi kết nối đến máy chủ.');
        }
    });

    // 2. Submit Form Thêm / Sửa Danh Mục
    document.getElementById('categoryApiForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('catId').value;
        
        const formData = new FormData();
        formData.append('name', document.getElementById('catName').value);
        formData.append('description', document.getElementById('catDesc').value);

        const catImageFile = document.getElementById('catImageFile').files[0];
        if (catImageFile) {
            formData.append('image', catImageFile); 
        }

        if (id) formData.append('_method', 'PUT');

        const token = localStorage.getItem('auth_token');
        const url = id ? `/api/categories/${id}` : '/api/categories';

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    ...(token ? { 'Authorization': `Bearer ${token}` } : {})
                },
                body: formData
            });

            const result = await response.json();
            if (response.ok) {
                alert(id ? 'Cập nhật danh mục thành công!' : 'Thêm danh mục thành công!');
                bootstrap.Modal.getInstance(document.getElementById('categoryModal')).hide();
                loadAdminCategories();
                loadCategoryDropdown();
            } else {
                alert('Lỗi: ' + (result.message || JSON.stringify(result.errors || 'Không thể lưu danh mục.')));
            }
        } catch (err) {
            console.error(err);
            alert('Lỗi kết nối đến máy chủ.');
        }
    });
});

// đường dẫn ảnh
function getFormattedImageUrl(imgSrc) {
    if (!imgSrc) return 'https://images.unsplash.com/photo-1526047932273-341f2a7631f9?q=80&w=200';
    if (imgSrc.startsWith('http')) return imgSrc;
    
    // Nếu đường dẫn đã có sẵn chữ storage/ -> giữ nguyên hoặc thêm dấu / ở trước
    if (imgSrc.startsWith('storage/')) {
        return '/' + imgSrc;
    }
    
    // Nếu ảnh danh mục được lưu dạng categories/ten_anh.jpg hoặc chỉ có tên file
    if (imgSrc.includes('categories/') || imgSrc.startsWith('categories')) {
        return '/storage/' + (imgSrc.startsWith('/') ? imgSrc.substring(1) : imgSrc);
    }

    // Mặc định cho sản phẩm trong storage/images/
    if (!imgSrc.startsWith('images/')) {
        return '/storage/images/' + imgSrc;
    }
    
    return '/storage/' + imgSrc;
}

// Tải danh sách sản phẩm
async function loadAdminProducts() {
    const tbody = document.getElementById('productsTableBody');
    tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-muted">Đang tải dữ liệu sản phẩm...</td></tr>`;

    try {
        const response = await fetch('/api/products', { headers: { 'Accept': 'application/json' } });
        const json = await response.json();
        let resData = json.data || json;
        let products = Array.isArray(resData) ? resData : (resData.data || []);

        if (products.length > 0) {
            let html = '';
            products.forEach(p => {
                let formattedPrice = Number(p.price || 0).toLocaleString('vi-VN') + ' đ';
                let catName = p.category ? p.category.name : 'Chưa phân loại';
                let mainImg = getFormattedImageUrl(p.image);

                let subImgsHtml = '';
                if (p.images && Array.isArray(p.images) && p.images.length > 0) {
                    subImgsHtml = '<div class="d-flex gap-1 mt-1 flex-wrap">';
                    p.images.forEach(sub => {
                        let subUrl = typeof sub === 'string' ? getFormattedImageUrl(sub) : getFormattedImageUrl(sub.image_path);
                        subImgsHtml += `<img src="${subUrl}" class="rounded border" style="width: 22px; height: 22px; object-fit: cover;" onerror="this.style.display='none'">`;
                    });
                    subImgsHtml += '</div>';
                }

                html += `
                    <tr>
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-2">
                                <div class="position-relative overflow-hidden rounded-3 border shadow-sm flex-shrink-0" style="width: 50px; height: 50px; border-color: #e5e2de !important;">
                                    <img src="${mainImg}" class="w-100 h-100 object-fit-cover" alt="Mẫu hoa" onerror="this.src='https://images.unsplash.com/photo-1561181286-d3fee7d55364?q=80&w=600'">
                                </div>
                                <div>${subImgsHtml}</div>
                            </div>
                        </td>
                        <td class="py-3">
                            <strong class="d-block mb-1 text-dark">${p.name}</strong>
                            <small class="text-muted">Mã SKU: BAF-SP${p.id}</small>
                        </td>
                        <td class="py-3">
                            <span class="badge rounded-pill px-3 py-2 fw-semibold" style="background-color: rgba(123, 17, 19, 0.08); color: #7b1113; border: 1px solid rgba(123, 17, 19, 0.15);">
                                ${catName}
                            </span>
                        </td>
                        <td class="py-3">
                            <span class="price-number fw-bold fs-6 text-dark">${formattedPrice}</span>
                        </td>
                        <td class="text-end py-3">
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1.5 shadow-sm hover-scale" data-bs-toggle="modal" data-bs-target="#productModal" onclick='editProduct(${p.id}, ${JSON.stringify(p.name)}, ${p.category_id}, ${p.price}, ${JSON.stringify(p.details || "")})' title="Chỉnh sửa">
                                    <i class="fa-solid fa-pen-to-square me-1"></i> Sửa
                                </button>
                                <button onclick="deleteProduct(${p.id})" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1.5 shadow-sm hover-scale" title="Xóa">
                                    <i class="fa-solid fa-trash me-1"></i> Xóa
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        } else {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-muted">Chưa có mẫu hoa nào trong cơ sở dữ liệu.</td></tr>`;
        }
    } catch (err) {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-danger">Lỗi kết nối tải dữ liệu sản phẩm.</td></tr>`;
    }
}

// Tải danh sách danh mục 
   async function loadAdminCategories() {
    const tbody = document.getElementById('categoriesTableBody');
    tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-muted">Đang tải danh mục...</td></tr>`;

    try {
        const response = await fetch('/api/categories', { headers: { 'Accept': 'application/json' } });
        const json = await response.json();
        const categories = json.data || json;

        if (categories && categories.length > 0) {
            let html = '';
            categories.forEach((cat, index) => {
                // SỬ DỤNG HÀM getFormattedImageUrl CHO ĐƯỜNG DẪN ẢNH DANH MỤC
                let catImg = getFormattedImageUrl(cat.image);

                html += `
                    <tr>
                        <td>
                            <div class="position-relative overflow-hidden rounded-3 border shadow-sm" style="width: 45px; height: 45px; border-color: #e5e2de !important;">
                                <img src="${catImg}" class="w-100 h-100 object-fit-cover" alt="Danh mục" onerror="this.src='https://images.unsplash.com/photo-1561181286-d3fee7d55364?q=80&w=200'">
                            </div>
                        </td>
                        <td><strong class="text-dark">#${index + 1}</strong></td>
                        <td><strong class="text-dark">${cat.name}</strong></td>
                        <td class="small text-muted">${cat.description || 'Không có mô tả'}</td>
                        <td class="text-end">
                            <div class="d-flex justify-content-end gap-1">
                                <button class="btn btn-sm btn-outline-primary px-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#categoryModal" onclick='editCategory(${cat.id}, ${JSON.stringify(cat.name)}, ${JSON.stringify(cat.description || "")})'>
                                    <i class="fa-solid fa-pen-to-square me-1"></i> Sửa
                                </button>
                                <button onclick="deleteCategory(${cat.id})" class="btn btn-sm btn-outline-danger px-3 rounded-pill">
                                    <i class="fa-solid fa-trash me-1"></i> Xóa
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        } else {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-muted">Chưa có danh mục nào.</td></tr>`;
        }
    } catch (err) {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-danger">Lỗi kết nối tải danh mục.</td></tr>`;
    }
}

// Tải danh mục vào dropdown chọn(sp)
async function loadCategoryDropdown() {
    const select = document.getElementById('prodCat');
    if (!select) return;
    try {
        const response = await fetch('/api/categories', { headers: { 'Accept': 'application/json' } });
        const json = await response.json();
        const categories = json.data || json;

        let options = '<option value="">-- Chọn danh mục --</option>';
        if (categories) {
            categories.forEach(cat => {
                options += `<option value="${cat.id}">${cat.name}</option>`;
            });
        }
        select.innerHTML = options;
    } catch (err) {
        console.error(err);
    }
}

//FORM SP
function resetProductForm() {
    document.getElementById('prodModalTitle').innerText = 'Thêm Mẫu Hoa Mới';
    document.getElementById('productApiForm').reset();
    document.getElementById('prodId').value = '';
}

function editProduct(id, name, catId, price, description) {
    document.getElementById('prodModalTitle').innerText = 'Chỉnh Sửa Mẫu Hoa #' + id;
    document.getElementById('prodId').value = id;
    document.getElementById('prodName').value = name;
    document.getElementById('prodCat').value = catId;
    document.getElementById('prodPrice').value = price;
    document.getElementById('prodDesc').value = description;
    document.getElementById('prodImageFile').value = '';
    document.getElementById('prodSubImagesFiles').value = '';
}

async function deleteProduct(id) {
    if (confirm('Bạn có chắc chắn muốn xóa mẫu hoa này khỏi hệ thống?')) {
        const token = localStorage.getItem('auth_token');
        try {
            const response = await fetch(`/api/products/${id}`, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', ...(token ? { 'Authorization': `Bearer ${token}` } : {}) }
            });
            if (response.ok) {
                alert('Xóa sản phẩm thành công!');
                loadAdminProducts();
            } else {
                alert('Lỗi khi xóa sản phẩm.');
            }
        } catch (err) {
            console.error(err);
            alert('Không thể xóa sản phẩm này.');
        }
    }
}

// FORM DM
function resetCategoryForm() {
    document.getElementById('catModalTitle').innerText = 'Thêm Danh Mục Mới';
    document.getElementById('categoryApiForm').reset();
    document.getElementById('catId').value = '';
    document.getElementById('catImageFile').value = '';
}

function editCategory(id, name, description) {
    document.getElementById('catModalTitle').innerText = 'Chỉnh Sửa Danh Mục #' + id;
    document.getElementById('catId').value = id;
    document.getElementById('catName').value = name;
    document.getElementById('catDesc').value = description;
    document.getElementById('catImageFile').value = '';
}

async function deleteCategory(id) {
    if (confirm('Bạn có chắc chắn muốn xóa danh mục này?')) {
        const token = localStorage.getItem('auth_token');
        try {
            const response = await fetch(`/api/categories/${id}`, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', ...(token ? { 'Authorization': `Bearer ${token}` } : {}) }
            });
            if (response.ok) {
                alert('Xóa danh mục thành công!');
                loadAdminCategories();
                loadCategoryDropdown();
            } else {
                alert('Lỗi khi xóa danh mục.');
            }
        } catch (err) {
            console.error(err);
            alert('Không thể xóa danh mục này vì có thể đang chứa sản phẩm liên quan.');
        }
    }
}
</script>
@endpush