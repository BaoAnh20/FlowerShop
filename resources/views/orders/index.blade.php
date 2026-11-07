@extends('layouts.app')

@section('title', 'Lịch Sử Đơn Hàng - BAFlorist')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <!-- Tiêu đề trang được căn giữa và làm nổi bật -->
    <div class="text-center mb-5">
        <h2 class="fw-bold mb-2 text-primary-custom" style="font-family: var(--font-heading);">
            Đơn Hàng Đã Đặt
        </h2>
        <p class="text-muted small mb-0">Quản lý và theo dõi trạng thái các đơn hoa đã đặt</p>
    </div>


    <!-- Danh sách đơn hàng -->
    <div class="d-flex flex-column gap-3">
        @if(isset($orders) && count($orders) > 0)
            @foreach($orders as $order)
                <div class="glass-blur-card p-4 shadow-sm rounded-4 border-0">
                    <!-- Header của Đơn hàng -->
                    <div class="d-flex justify-content-between align-items-center pb-3 border-bottom flex-wrap gap-2" style="border-color: rgba(123, 17, 19, 0.08) !important;">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <span class="fw-bold text-dark">
                                Mã đơn: <span class="text-primary-custom price-number">#{{ $order->code ?? 'BAF-' . $order->id }}</span>
                            </span>
                            <span class="text-muted">|</span>
                            <small class="text-muted">
                                Ngày đặt: <span class="fw-medium text-dark">{{ optional($order->created_at)->format('d/m/Y H:i') ?? '18/10/2026 14:30' }}</span>
                            </small>
                        </div>
                        <div>
                            @php
                                $statusClass = match($order->status ?? 'pending') {
                                    'pending' => 'bg-warning-subtle text-warning border-warning-subtle',
                                    'completed' => 'bg-success-subtle text-success border-success-subtle',
                                    default => 'bg-secondary-subtle text-secondary'
                                };
                                $statusText = match($order->status ?? 'pending') {
                                    'pending' => 'Chờ xác nhận',
                                    'completed' => 'Đã hoàn thành',
                                    default => 'Đang xử lý'
                                };
                            @endphp
                            <span class="badge border px-3 py-2 rounded-pill fw-semibold {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </div>
                    </div>

                    <!-- Danh sách sản phẩm xem trước trong đơn -->
                    <div class="py-3">
                        @if(isset($order->items) && count($order->items) > 0)
                            @foreach($order->items as $item)
                                <div class="d-flex align-items-center justify-content-between py-2 border-bottom border-light">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ isset($item->product->image_path) ? asset('storage/' . $item->product->image_path) : 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?q=80&w=150' }}" 
                                             class="rounded-3 object-fit-cover shadow-sm border" 
                                             width="55" height="55" 
                                             alt="{{ $item->product->name ?? 'Mẫu hoa tươi' }}"
                                             onerror="this.src='https://images.unsplash.com/photo-1561181286-d3fee7d55364?q=80&w=150'">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.92rem;">{{ $item->product->name ?? 'Bó Hoa Hồng Pastel Tinh Khôi' }}</h6>
                                            <small class="text-muted">SL: x{{ $item->quantity ?? 1 }}  Đơn giá: {{ number_format($item->price ?? 450000, 0, ',', '.') }} đ</small>
                                        </div>
                                    </div>
                                    <div class="text-end fw-bold text-dark price-number" style="font-size: 0.95rem;">
                                        {{ number_format(($item->price ?? 450000) * ($item->quantity ?? 1), 0, ',', '.') }} đ
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <!-- Fallback dữ liệu tĩnh -->
                            <div class="d-flex align-items-center justify-content-between py-2">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="https://images.unsplash.com/photo-1561181286-d3fee7d55364?q=80&w=150" 
                                         class="rounded-3 object-fit-cover shadow-sm border" 
                                         width="55" height="55" 
                                         alt="Hoa tươi">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-1" style="font-size: 0.92rem;">Bó Hoa Hồng Pastel Tinh Khôi</h6>
                                        <small class="text-muted">SL: x1  Đơn giá: 450.000 đ</small>
                                    </div>
                                </div>
                                <div class="text-end fw-bold text-dark price-number" style="font-size: 0.95rem;">
                                    450.000 đ
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Footer Đơn hàng & Thao tác -->
                    <div class="d-flex justify-content-between align-items-center pt-2 flex-wrap gap-3">
                        <div class="small text-muted">
                            <span>Thông tin người nhận: <strong class="text-dark">{{ $order->recipient_name ?? 'Bảo Anh' }}</strong> ({{ $order->recipient_phone ?? '0901234567' }})</span>
                            <span class="mx-2">&bull;</span>
                            <span>Giờ giao: <strong class="text-dark">{{ optional($order->delivery_time)->format('H:i - d/m/Y') ?? '09:00 - 19/10/2026' }}</strong></span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="text-end">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Tổng thanh toán:</small>
                                <span class="fs-6 fw-bold price-number text-primary-custom">{{ number_format($order->total_price ?? 450000, 0, ',', '.') }} đ</span>
                            </div>
                            <a href="{{ route('orders.show', $order->id ?? 1) }}" class="btn btn-primary-custom btn-sm rounded-pill px-4 py-2 fw-semibold shadow-sm text-nowrap">
                                Xem chi tiết <i class="fa-solid fa-angle-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Phân trang -->
            @if(method_exists($orders, 'hasPages') && $orders->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
            @endif

        @else
            <!-- Trạng thái trống / Fallback mẫu -->
            @for($i = 1; $i <= 2; $i++)
                <div class="glass-blur-card p-4 shadow-sm rounded-4 border-0">
                    <div class="d-flex justify-content-between align-items-center pb-3 border-bottom flex-wrap gap-2" style="border-color: rgba(123, 17, 19, 0.08) !important;">
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <span class="fw-bold text-dark">
                                Mã đơn: <span class="text-primary-custom price-number">#BAF-202600{{ $i }}</span>
                            </span>
                            <span class="text-muted">|</span>
                            <small class="text-muted">
                                Ngày đặt: <span class="fw-medium text-dark">18/10/2026 10:15</span>
                            </small>
                        </div>
                        <div>
                            @if($i == 1)
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-2 rounded-pill fw-semibold">
                                    Chờ xác nhận
                                </span>
                            @else
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-semibold">
                                    Đã hoàn thành
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="py-3">
                        <div class="d-flex align-items-center justify-content-between py-2">
                            <div class="d-flex align-items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1561181286-d3fee7d55364?q=80&w=150" 
                                     class="rounded-3 object-fit-cover shadow-sm border" 
                                     width="55" height="55" 
                                     alt="Hoa tươi">
                                <div>
                                    <h6 class="fw-bold text-dark mb-1" style="font-size: 0.92rem;">Bó Hoa Hồng Pastel Tinh Khôi {{ $i }}</h6>
                                    <small class="text-muted">Số lượng: x1 <br> Đơn giá: 450.000 đ</small>
                                </div>
                            </div>
                            <div class="text-end fw-bold text-dark price-number" style="font-size: 0.95rem;">
                                450.000 đ
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-2 flex-wrap gap-3">
                        <div class="small text-muted">
                            <span>Thông tin người nhận: <strong class="text-dark">Bảo Anh</strong> (0901234567)</span>
                            <br>
                            <span>Giờ giao: <strong class="text-dark">09:00 - 19/10/2026</strong></span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="text-end">
                                <small class="text-muted d-block" style="font-size: 0.75rem;">Tổng thanh toán:</small>
                                <span class="fs-6 fw-bold price-number text-primary-custom">450.000 đ</span>
                            </div>
                            <a href="{{ route('orders.show', $i) }}" class="btn btn-primary-custom btn-sm rounded-pill px-4 py-2 fw-semibold shadow-sm text-nowrap">
                                Xem chi tiết <i class="fa-solid fa-angle-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endfor
        @endif
    </div>
</div>
@endsection