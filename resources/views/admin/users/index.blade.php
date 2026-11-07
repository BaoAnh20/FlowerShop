@extends('layouts.admin')

@section('title', 'Quản Lý Khách Hàng - BAFlorist Admin')

@section('page_title', 'Danh Sách Tài Khoản Khách Hàng')

@section('content')
<!-- list khách hàng-->
<div class="card border-0 shadow-none bg-transparent p-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr class="small border-bottom text-uppercase">
                    <th style="color: #5c0d0f !important; width: 80px;">ID</th>
                    <th style="color: #5c0d0f !important;">HỌ VÀ TÊN</th>
                    <th style="color: #5c0d0f !important;">EMAIL / SĐT</th>
                    <th style="color: #5c0d0f !important;">NGÀY ĐĂNG KÝ</th>
                    <th style="color: #5c0d0f !important;">TỔNG ĐƠN ĐÃ ĐẶT</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong style="color: #1f2937 !important;">#1</strong></td>
                    <td><strong style="color: #1f2937 !important;"> Thị Bảo Anh</strong></td>
                    <td><span style="color: #4b5563 !important;">baoanh@gmail.com</span></td>
                    <td><span style="color: #4b5563 !important;">15/1/2026</span></td>
                    <td>
                        <span class="badge px-3 py-1.5 fw-bold" style="background-color: rgba(16, 185, 129, 0.15); color: #059669; border: 1px solid rgba(16, 185, 129, 0.3);">
                            2 đơn hàng
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection