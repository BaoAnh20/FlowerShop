@extends('layouts.app')

@section('title', 'Giỏ Hàng & Thanh Toán - BAFlorist')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <h3 class="fw-bold mb-4 text-primary-custom d-flex align-items-center gap-2">
        <i class="fa-solid fa-bag-shopping"></i> Giỏ Hàng Của Bạn
    </h3>
    
    @php $totalPrice = 0; @endphp

    @if(isset($cartItems) && count($cartItems) > 0)
    <div class="row g-4">
        <!-- list hoa trong giỏ -->
        <div class="col-lg-7">
            <div class="glass-blur-card p-4 shadow-sm mb-4 bg-white">
                <div class="table-responsive">
                    <table class="table align-middle bg-transparent mb-0">
                        <thead>
                            <tr class="text-secondary border-bottom">
                                <th>Sản phẩm</th>
                                <th>Đơn giá</th>
                                <th style="width: 90px;">SL</th>
                                <th>Thành tiền</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                                @php 
                                    $subtotal = $item->product->price * $item->quantity;
                                    $totalPrice += $subtotal;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ asset('storage/' . $item->product->image_path) }}" 
                                                 class="rounded-3 shadow-sm object-fit-cover border" 
                                                 width="60" height="60"
                                                 onerror="this.src='https://images.unsplash.com/photo-1561181286-d3fee7d55364?q=80&w=150'">
                                            <div>
                                                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.92rem;">{{ $item->product->name }}</h6>
                                                <small class="text-muted">{{ $item->size ?? 'Kích thước chuẩn' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="fw-medium text-nowrap small price-number">{{ number_format($item->product->price, 0, ',', '.') }} đ</td>
                                    <td>
                                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="99" 
                                                   class="form-control form-control-sm rounded-3 text-center border bg-light shadow-none price-number" 
                                                   onchange="this.form.submit()">
                                        </form>
                                    </td>
                                    <td class="fw-bold text-nowrap price-number text-primary-custom">{{ number_format($subtotal, 0, ',', '.') }} đ</td>
                                    <td>
                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0 border-0" onclick="return confirm('Bạn muốn xóa sản phẩm này?')">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            <a href="{{ route('products.index') }}" class="btn btn-outline-custom text-decoration-none fw-semibold small py-2 px-3">
                <i class="fa-solid fa-arrow-left me-1"></i> Tiếp tục chọn thêm hoa
            </a>
        </div>

        <!-- Đặt hàng & Thanh toán -->
        <div class="col-lg-5">
            <div class="glass-blur-card p-4 shadow-sm bg-white">
                <h5 class="fw-bold mb-3 text-primary-custom d-flex align-items-center gap-2">
                    <i class="fa-solid fa-truck-ramp-box"></i> Thông Tin Giao Hoa
                </h5>
                
                <form action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Họ và tên người nhận <span class="text-danger">*</span></label>
                        <input type="text" name="recipient_name" class="form-control form-control-sm rounded-pill px-3 py-2 border-0 bg-light shadow-none" 
                               value="{{ old('recipient_name', Auth::user()->name ?? '') }}" placeholder="Nguyễn Văn A" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Số điện thoại người nhận <span class="text-danger">*</span></label>
                        <input type="tel" name="recipient_phone" class="form-control form-control-sm rounded-pill px-3 py-2 border-0 bg-light shadow-none" 
                               value="{{ old('recipient_phone', Auth::user()->phone ?? '') }}" placeholder="090x xxx xxx" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Địa chỉ giao hoa tận nơi <span class="text-danger">*</span></label>
                        <input type="text" name="shipping_address" class="form-control form-control-sm rounded-pill px-3 py-2 border-0 bg-light shadow-none" 
                               value="{{ old('shipping_address') }}" placeholder="Số nhà, Tên đường, Phường/Xã..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-primary-custom">
                            <i class="fa-regular fa-clock me-1"></i>Thời gian giao hoa mong muốn <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local" name="delivery_time" min="{{ now()->format('Y-m-d\TH:i') }}" class="form-control form-control-sm rounded-pill px-3 py-2 border-0 bg-light shadow-none" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-primary-custom">
                            <i class="fa-solid fa-envelope-open-text me-1"></i>Nội dung ghi trên thiệp / Băng rôn
                        </label>
                        <textarea name="card_message" class="form-control form-control-sm rounded-4 p-3 border-0 bg-light shadow-none" rows="2" placeholder="Chúc mừng sinh nhật / Kỷ niệm ngày cưới..."></textarea>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input shadow-none custom-checkbox" type="checkbox" name="is_anonymous" value="1" id="anonymous">
                        <label class="form-check-label small text-secondary cursor-pointer" for="anonymous">
                            Giao hoa ẩn danh (Bảo mật tuyệt đối thông tin người gửi)
                        </label>
                    </div>

                    <hr class="opacity-25">

                    <div class="d-flex justify-content-between align-items-center fw-bold fs-5 mb-3">
                        <span class="text-dark">Tổng thanh toán:</span>
                        <span class="fs-4 price-number text-primary-custom">{{ number_format($totalPrice, 0, ',', '.') }} đ</span>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 py-3 fw-bold text-uppercase fs-6 shadow-sm">
                        Xác Nhận Đặt Hàng
                    </button>
                </form>
            </div>
        </div>
    </div>
    @else
        <!--cart trống -->
        <div class="text-center py-5 glass-blur-card bg-white">
            <i class="fa-solid fa-basket-shopping fs-1 text-muted mb-3"></i>
            <h5 class="fw-bold text-secondary">Giỏ hàng của bạn đang trống!</h5>
            <p class="text-muted small">Chưa có sản phẩm nào được chọn. Hãy khám phá các bó hoa tươi đẹp nhé.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary-custom px-4 mt-2">
                <i class="fa-solid fa-spa me-1"></i> Khám phá hoa tươi
            </a>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .custom-checkbox:checked {
        background-color: var(--burgundy-primary);
        border-color: var(--burgundy-primary);
    }
</style>
@endpush