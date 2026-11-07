@extends('layouts.admin')

@section('title', 'Quản Lý Đơn Hàng - BAFlorist Admin')
@section('page_title', 'Danh Sách Đơn Hàng')

@section('content')
<!-- Table list đơn hàng -->
<div class="card border-0 shadow-sm bg-white p-3" style="border-radius: 16px;">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr class="small border-bottom text-uppercase" style="background-color: #f9f8f6;">
                    <th style="color: #5c0d0f !important; padding: 14px 12px;">MÃ ĐƠN</th>
                    <th style="color: #5c0d0f !important; padding: 14px 12px;">NGƯỜI ĐẶT</th>
                    <th style="color: #5c0d0f !important; padding: 14px 12px;">NGƯỜI NHẬN</th>
                    <th style="color: #5c0d0f !important; padding: 14px 12px;">THỜI GIAN GIAO</th>
                    <th style="color: #5c0d0f !important; padding: 14px 12px;">TỔNG TIỀN</th>
                    <th style="color: #5c0d0f !important; padding: 14px 12px;">TRẠNG THÁI</th>
                    <th class="text-end" style="color: #5c0d0f !important; padding: 14px 12px;">THAO TÁC</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong class="text-dark">#BAF-20261019-01</strong></td>
                    <td><span class="fw-semibold text-dark">Nguyễn Văn A</span></td>
                    <td><span class="fw-semibold text-dark">Nguyễn Thị Bảo Anh</span></td>
                    <td><span class="text-muted">19/10/2026 (Chiều)</span></td>
                    <td><span class="price-number fw-bold text-dark">7.900.000 đ</span></td>
                    <td>
                        <!-- Badge trạng thái (Nền màu, chữ trắng) -->
                        <span class="badge px-3 py-1.5 fw-bold text-white" style="background-color: #f59e0b; border: 1px solid #d97706;">
                            Chờ xác nhận
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('admin.orders.show', 1) }}" class="btn btn-sm btn-outline-primary px-3 fw-semibold rounded-pill shadow-sm">
                            <i class="fa-solid fa-eye me-1"></i> Xem
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection