@extends('layouts.app')

@section('title', 'Chi Tiết Sản Phẩm - BAFlorist')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4" id="productDetailContainer">
    <div class="row justify-content-center py-5">
        <div class="col-12 text-center py-5">
            <i class="fa-solid fa-spinner fa-spin fs-2 text-primary-custom mb-3"></i>
            <p class="text-muted small">Đang tải thông tin chi tiết sản phẩm hoa...</p>
        </div>
    </div>
</div>

<!-- Phóng To Ảnh -->
<div class="modal fade" id="imageLightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0 position-relative">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-4 z-3 shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body text-center p-0 d-flex align-items-center justify-content-center" style="min-height: 80vh;">
                <img id="lightboxImage" src="" class="img-fluid rounded-4 shadow-lg object-fit-contain" style="max-height: 85vh; max-width: 100%;" alt="Phóng to ảnh sản phẩm">
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .product-outline-btn:hover {
        background-color: #f8f8f8 !important;
        color: #000000 !important;
        border-color: #000000 !important;
    }
    .product-thumbnail {
        cursor: pointer;
        opacity: 0.6;
        border: 2px solid transparent;
        transition: all 0.25s ease;
    }
    .product-thumbnail:hover, .product-thumbnail.active {
        opacity: 1;
        border-color: var(--burgundy-primary);
        transform: translateY(-2px);
    }
    #imageLightboxModal .modal-content {
        background-color: rgba(0, 0, 0, 0.92);
    }
    #imageLightboxModal .btn-close {
        filter: invert(1);
    }
    
    /* --- CĂN GIỮA ẢNH PHỤ --- */
    .thumbnails-wrapper {
        width: 100%;
        display: flex;
        justify-content: center; /* Căn giữa toàn bộ cụm ảnh phụ */
    }
    .thumbnails-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        max-width: 100%;
        overflow-x: auto;
        padding-bottom: 5px;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const pathSegments = window.location.pathname.split('/');
        const productId = pathSegments[pathSegments.length - 1];

        if (productId && !isNaN(productId)) {
            fetchProductDetail(productId);
        } else {
            showErrorState("Không tìm thấy mã sản phẩm hợp lệ.");
        }
    });

    async function fetchProductDetail(id) {
        const container = document.getElementById('productDetailContainer');

        try {
            const result = await window.apiService.getProductDetail(id);

            if (result.ok && result.data.success) {
                const product = result.data.data;
                document.title = `${product.name} - BAFlorist`;

                // Xử lý danh sách ảnh
             // Xử lý danh sách ảnh (Luôn ưu tiên đưa ảnh chính lên vị trí đầu tiên)
let imagesList = [];
if (product.image) {
    imagesList.push(product.image);
}
if (product.images && Array.isArray(product.images) && product.images.length > 0) {
    product.images.forEach(img => {
        const subPath = img.image_path || img;
        // Tránh thêm trùng lặp nếu ảnh chính đã nằm trong mảng ảnh phụ
        if (subPath !== product.image && !imagesList.includes(subPath)) {
            imagesList.push(subPath);
        }
    });
}
if (imagesList.length === 0) {
    imagesList = ['https://images.unsplash.com/photo-1518709268805-4e9042af9f23?q=80&w=800'];
}

                // đường dẫn các ảnh
                imagesList = imagesList.map(img => {
                    if (!img.startsWith('http')) {
                        if (!img.startsWith('storage/') && !img.startsWith('images/')) {
                            return '/storage/images/' + img;
                        } else if (img.startsWith('images/')) {
                            return '/storage/' + img;
                        } else {
                            return '/' + img;
                        }
                    }
                    return img;
                });

                let categoryName = product.category ? product.category.name : 'Hoa tươi';
                let formattedPrice = Number(product.price).toLocaleString('vi-VN') + ' đ';

                // Render giao diện
                container.innerHTML = `
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="/" class="text-decoration-none text-primary-custom">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="/products" class="text-decoration-none text-primary-custom">Sản phẩm</a></li>
                            <li class="breadcrumb-item active text-truncate text-secondary" style="max-width: 250px;" aria-current="page">
                                ${product.name}
                            </li>
                        </ol>
                    </nav>

                    <div class="row g-5 align-items-start">
                        <!-- Cột ảnh sản phẩm bên trái -->
                        <div class="col-lg-6">
                            <div class="d-flex flex-column">
                                <div class="w-100 overflow-hidden rounded-4 mb-3 position-relative cursor-pointer bg-light" style="max-height: 480px;" onclick="openLightbox(document.getElementById('mainProductImage').src)" title="Click để xem ảnh lớn">
                                    <img id="mainProductImage" src="${imagesList[0]}" class="img-fluid w-100 h-100 object-fit-contain rounded-4 shadow-none" style="max-height: 480px;" alt="${product.name}">
                                </div>
${imagesList.length > 1 ? ` 
    <div class="thumbnails-wrapper mt-3">
        <div class="thumbnails-container"> 
            ${imagesList.map((img, index) => ` 
                <div class="rounded-3 overflow-hidden flex-shrink-0 product-thumbnail ${index === 0 ? 'active' : ''}" style="width: 75px; height: 75px;" onclick="changeMainImage('${img}', this)"> 
                    <img src="${img}" class="w-100 h-100 object-fit-cover rounded-3" alt="Thumbnail ${index + 1}"> 
                </div> 
            `).join('')} 
        </div>
    </div> 
` : ''}
                            </div>
                        </div>

                        <!-- Cột thông tin sản phẩm bên phải -->
                        <div class="col-lg-6 d-flex flex-column pt-2">
                            <span class="text-muted small mb-2 text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Danh mục: ${categoryName}</span>
                            
                            <h1 class="fw-normal mb-2 text-dark" style="font-size: 2.2rem;">
                                ${product.name}
                            </h1> 

                            <div class="mb-4"> 
                                <span class="fw-bold fs-2 price-number text-primary-custom" style="letter-spacing: -0.5px;"> 
                                    ${formattedPrice} 
                                </span> 
                            </div> 

                            <!-- Dòng hiển thị Loại hoa chủ đạo -->
                            <div class="mb-3 d-flex align-items-center gap-2">
                                <span class="text-secondary small fw-semibold">Hoa chính:</span>
                                <span class="badge bg-light text-dark border px-3 py-1.5 rounded-pill fw-normal" style="font-size: 0.85rem;">
                                    ${product.primary_flower || 'Hoa tươi thiết kế'}
                                </span>
                            </div>

                            <!-- Nội dung miêu tả chi tiết sản phẩm (Đã bỏ tiêu đề "Ý nghĩa...") -->
                            <div class="mb-4 text-secondary small" style="line-height: 1.7;">
                                <div class="text-secondary">
                                    ${product.details || product.meaning || 'Mỗi đóa hoa trong mẫu thiết kế mang theo thông điệp yêu thương và lời chúc chân thành nhất dành tặng người nhận.'}
                                </div>
                            </div>

                            <!-- Khung cam kết --> 
                            <div class="p-3 mb-4 rounded-4 shadow-sm bg-white" style="border: 1px solid rgba(0, 0, 0, 0.08);"> 
                                <div class="row text-center g-2"> 
                                    <div class="col-6 border-end" style="border-color: rgba(0, 0, 0, 0.06) !important;"> 
                                        <div class="py-1"> 
                                            <i class="fa-solid fa-truck mb-1 text-primary-custom" style="font-size: 1.1rem;"></i> 
                                            <div class="fw-bold text-dark" style="font-size: 0.8rem;">Miễn phí ship</div> 
                                            <div class="text-muted" style="font-size: 0.7rem;">Đơn từ 1.000.000đ nội thành</div> 
                                        </div> 
                                    </div> 
                                    <div class="col-6"> 
                                        <div class="py-1"> 
                                            <i class="fa-solid fa-feather mb-1 text-primary-custom" style="font-size: 1.1rem;"></i> 
                                            <div class="fw-bold text-dark" style="font-size: 0.8rem;">Thiệp viết tay</div> 
                                            <div class="text-muted" style="font-size: 0.7rem;">Chữ viết tỉ mỉ</div> 
                                        </div> 
                                    </div> 
                                </div> 
                            </div> 

                            <!-- Form Thêm vào giỏ & Mua ngay --> 
                            <form action="/cart/add" method="POST" id="productForm">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''}">
                                <input type="hidden" name="product_id" value="${product.id}"> 
                                <input type="hidden" name="quantity" value="1"> 
                                <div class="d-flex align-items-center gap-3 flex-wrap"> 
                                    <button type="submit" class="btn rounded-pill px-4 py-3 fw-semibold bg-white flex-grow-1 shadow-sm product-outline-btn" style="border: 1px solid #111111; color: #111111; transition: all 0.2s;"> 
                                        Thêm vào giỏ 
                                    </button> 
                               <a href="/checkout?product_id=${product.id}" class="btn rounded-pill px-4 py-3 fw-semibold text-white flex-grow-1 shadow-sm text-center text-decoration-none btn-primary-custom"> 
    Đặt ngay 
</a>
                                </div> 
                            </form> 
                        </div> 
                    </div>
                `;
            } else {
                showErrorState("Không tìm thấy thông tin sản phẩm!");
            }
        } catch (error) {
            console.error('Lỗi khi tải chi tiết sản phẩm:', error);
            showErrorState("Lỗi kết nối đến máy chủ.");
        }
    }

    function changeMainImage(imgSrc, element) {
        document.getElementById('mainProductImage').src = imgSrc;
        document.querySelectorAll('.product-thumbnail').forEach(thumb => thumb.classList.remove('active'));
        element.classList.add('active');
    }

    function openLightbox(imgSrc) {
        document.getElementById('lightboxImage').src = imgSrc;
        const lightboxModal = new bootstrap.Modal(document.getElementById('imageLightboxModal'));
        lightboxModal.show();
    }

    function showErrorState(message) {
        const container = document.getElementById('productDetailContainer');
        container.innerHTML = `
            <div class="row justify-content-center py-5">
                <div class="col-12 text-center py-5">
                    <i class="fa-solid fa-triangle-exclamation fs-1 text-danger mb-3"></i>
                    <h5 class="fw-bold text-secondary">${message}</h5>
                    <a href="/products" class="btn btn-primary-custom rounded-pill px-4 mt-3">Quay lại danh sách sản phẩm</a>
                </div>
            </div>
        `;
    }
</script>
@endpush
@endsection