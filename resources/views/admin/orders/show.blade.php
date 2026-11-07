@extends('layouts.admin')

@section('title', 'Chi Tiết Đơn Hàng - BAFlorist Admin')

@section('page_title')
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h4 class="fw-bold mb-1" style="font-family: var(--font-heading) !important; color: #5c0d0f !important;">
                Chi Tiết Đơn Hàng #{{ $id ?? 'BAF-20261019-01' }}
            </h4>
            <small class="fw-semibold text-muted" id="dynamic-greeting">
                Chào buổi chiều, Admin Bảo Anh
            </small>
        </div>
    </div>
@endsection

@section('content')
<div class="row g-4">
    <!-- Cột thông tin đơn & Sản phẩm -->
    <div class="col-md-8">
        <!-- Sản Phẩm Đặt Mua -->
        <div class="card border-0 shadow-sm bg-white p-4 mb-4" style="border-radius: 16px;">
            <h6 class="fw-bold mb-3" style="font-family: var(--font-heading); color: #5c0d0f;">Sản Phẩm Đặt Mua</h6>
            
            <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <img src="https://images.unsplash.com/photo-1526047932273-341f2a7631f9?q=80&w=200" class="rounded-3 border shadow-sm" width="60" height="60" alt="Product" style="border-color: #e5e2de !important;">
                    <div>
                        <h6 class="fw-bold mb-1 text-dark small">Hộp mica hoa hồng hồng Pink Letter</h6>
                        <small class="text-muted">Mã SKU: BAF-HD01 | Số lượng: x1</small>
                    </div>
                </div>
                <span class="price-number fw-bold text-dark">7.900.000 đ</span>
            </div>
            
            <div class="pt-3 text-end">
                <span class="fw-bold text-dark">Tổng đơn hàng: </span>
                <span class="price-number fs-4 fw-bold ms-2" style="color: #7b1113;">7.900.000 đ</span>
            </div>
        </div>

        <!-- Nội Dung Thiệp & Ghi Chú -->
        <div class="card border-0 shadow-sm bg-white p-4" style="border-radius: 16px;">
            <h6 class="fw-bold mb-3" style="font-family: var(--font-heading); color: #5c0d0f;">Nội Dung Thiệp & Ghi Chú</h6>
            <div class="p-3 rounded-3 fst-italic small border text-dark bg-light" style="border-color: #e5e2de !important;">
                "Chúc mừng sinh nhật em yêu! Chúc em luôn rạng rỡ và hạnh phúc."
            </div>
        </div>
    </div>

    <!-- Cột xử lý trạng thái & Giao nhận -->
    <div class="col-md-4">
        <!-- Nút quay lại nằm sát lề phải, phía trên card Cập Nhật Trạng Thái -->
        <div class="d-flex justify-content-end mb-2">
            <a href="{{ route('admin.orders.index') }}" class="text-decoration-none fw-semibold d-inline-flex align-items-center gap-1" style="color: #7b1113; font-size: 0.85rem !important;">
                <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>

        <!-- Dropdown Cập nhật trạng thái -->
        <div class="card border-0 shadow-sm bg-white p-4 mb-4" style="border-radius: 16px;">
            <h6 class="fw-bold mb-3" style="font-family: var(--font-heading); color: #5c0d0f;">Cập Nhật Trạng Thái</h6>
            <form action="{{ route('admin.orders.update_status', $id ?? 1) }}" method="POST">
                @csrf
                @method('PATCH')
                <select name="status" class="form-select px-3 py-2.5 bg-light border shadow-none mb-3 fw-semibold rounded-3 text-dark" style="border-color: #e5e2de !important;">
                    <option value="pending" selected>Chờ xác nhận</option>
                    <option value="processing">Đang cắm hoa</option>
                    <option value="shipping">Đang giao hàng</option>
                    <option value="completed">Đã giao thành công</option>
                    <option value="cancelled">Đã hủy đơn</option>
                </select>
                <button type="submit" class="btn btn-sm w-100 py-2.5 fw-bold shadow-sm rounded-3 text-white" style="background-color: #7b1113; border: none;">
                    Lưu Trạng Thái
                </button>
            </form>
        </div>

        <!-- Thông tin giao nhận -->
        <div class="card border-0 shadow-sm bg-white p-4" style="border-radius: 16px;">
            <h6 class="fw-bold mb-3" style="font-family: var(--font-heading); color: #5c0d0f;">Thông Tin Giao Hoa</h6>
            <div class="small d-flex flex-column gap-2 text-dark">
                <div>Người nhận: <strong class="text-dark">Nguyễn Thị Bảo Anh</strong></div>
                <div>Số điện thoại: <strong class="text-dark">0901234567</strong></div>
                <div>Địa chỉ giao: <strong class="text-dark">180 Cao Lỗ, Phường 4, Quận 8, TP.HCM</strong></div>
            </div>
        </div>
    </div>
</div>
@endsection