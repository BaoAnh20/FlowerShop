@extends('layouts.admin')

@section('title', 'Thống Kê & Báo Cáo - BAFlorist Admin')

@section('page_title', 'Báo Cáo Doanh Thu & Bán Hàng')

@section('content')
<div class="row g-4 mb-4">
    <!-- Biểu đồ doanh thu -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-white p-4" style="border-radius: 16px;">
            <h6 class="fw-bold mb-3" style="font-family: var(--font-heading); color: #5c0d0f;">Doanh Thu Theo Tháng</h6>
            <div class="p-5 text-center rounded-4 border d-flex flex-column align-items-center justify-content-center" style="min-height: 250px; background: #f9f8f6 !important; border-color: #e5e2de !important;">
                <i class="fa-solid fa-chart-column fs-1 mb-2" style="color: #7b1113;"></i>
                <p class="mb-0 fw-semibold text-dark">Biểu đồ doanh thu (Tích hợp Chart.js)</p>
            </div>
        </div>
    </div>
    
    <!-- Biểu đồ đơn hàng -->
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-white p-4" style="border-radius: 16px;">
            <h6 class="fw-bold mb-3" style="font-family: var(--font-heading); color: #5c0d0f;">Tỷ Lệ Trạng Thái Đơn Hàng</h6>
            <div class="p-5 text-center rounded-4 border d-flex flex-column align-items-center justify-content-center" style="min-height: 250px; background: #f9f8f6 !important; border-color: #e5e2de !important;">
                <i class="fa-solid fa-chart-pie fs-1 mb-2" style="color: #7b1113;"></i>
                <p class="mb-0 fw-semibold text-dark">Biểu đồ cơ cấu đơn hàng</p>
            </div>
        </div>
    </div>
</div>
@endsection