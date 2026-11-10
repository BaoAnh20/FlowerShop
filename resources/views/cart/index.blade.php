@extends('layouts.app')

@section('title', 'Giỏ Hàng - BAFlorist')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">
    <div class="text-center mb-4">
        <h3 class="fw-bold text-primary-custom d-inline-flex align-items-center gap-2 m-0">
            <i class="fa-solid fa-bag-shopping"></i> Giỏ Hàng Của Bạn
        </h3>
    </div>

    @php 
        $totalPrice = 0; 
        $cartItems = isset($cartItems) ? $cartItems : [];
    @endphp

    @if(count($cartItems) > 0)
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="glass-blur-card p-4 shadow-sm mb-4 bg-white rounded-4">
                    <div class="table-responsive">
                        <table class="table align-middle bg-transparent mb-0">
                            <thead>
                                <tr class="text-secondary border-bottom">
                                    <th class="py-3">Sản phẩm</th>
                                    <th class="py-3">Đơn giá</th>
                                    <th class="py-3 text-center" style="width: 110px;">Số lượng</th>
                                    <th class="py-3">Thành tiền</th>
                                    <th class="py-3 text-center" style="width: 50px;">Xóa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                    @php 
                                        $pId = $item->product->id ?? $item->product_id ?? '#'; 
                                        $pName = $item->product->name ?? $item->name ?? 'Sản phẩm'; 
                                        $pPrice = $item->product->price ?? $item->price ?? 0; 
                                        $pImage = $item->product->image ?? $item->image ?? ''; 
                                        
                                        // Chuẩn hóa đường dẫn ảnh từ cơ sở dữ liệu
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
                                        }

                                        $subtotal = $pPrice * $item->quantity;
                                        $totalPrice += $subtotal;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <a href="/product/{{ $pId }}">
                                                    <img src="{{ $imgUrl }}" class="rounded-3 shadow-sm object-fit-cover border" width="70" height="70" alt="{{ $pName }}" onerror="this.src='https://images.unsplash.com/photo-1561181286-d3fee7d55364?q=80&w=300'">
                                                </a>
                                                <div>
                                                    <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">
                                                        <a href="/product/{{ $pId }}" class="text-decoration-none text-dark hover-burgundy">
                                                            {{ $pName }}
                                                        </a>
                                                    </h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-medium text-nowrap small price-number text-secondary">{{ number_format($pPrice, 0, ',', '.') }} đ</td>
                                        <td class="text-center">
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex align-items-center justify-content-center">
                                                @csrf
                                                @method('PATCH')
                                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="99" class="form-control form-control-sm rounded-3 text-center border bg-light shadow-none price-number fw-bold" style="width: 70px;" onchange="this.form.submit()">
                                            </form>
                                        </td>
                                        <td class="fw-bold text-nowrap price-number text-primary-custom">{{ number_format($subtotal, 0, ',', '.') }} đ</td>
                                        <td class="text-center">
                                            <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-muted p-0 border-0 shadow-none hover-danger" title="Xóa sản phẩm" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')">
                                                    <i class="fa-solid fa-trash-can fs-6"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Tổng tiền - thanh toán -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-4 border-top gap-3">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-custom text-decoration-none fw-semibold small py-2 px-3 rounded-pill">
                            <i class="fa-solid fa-arrow-left me-1"></i> Tiếp tục chọn thêm hoa
                        </a>

                        <div class="d-flex align-items-center gap-4 w-100 w-md-auto justify-content-end">
                            <div class="text-end">
                                <span class="text-secondary small d-block">Tổng thanh toán:</span>
                                <span class="fs-4 fw-bold price-number text-primary-custom">{{ number_format($totalPrice, 0, ',', '.') }} đ</span>
                            </div>
                            <a href="/checkout" class="btn btn-primary-custom py-3 px-5 fw-bold text-uppercase rounded-pill shadow-sm text-decoration-none text-nowrap">
                                Tiến hành đặt hàng <i class="fa-solid fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- trống-->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center py-5 glass-blur-card bg-white rounded-4 shadow-sm">
                    <i class="fa-solid fa-basket-shopping fs-1 text-muted mb-3 text-primary-custom opacity-50"></i>
                    <h5 class="fw-bold text-secondary">Giỏ hàng của bạn đang trống!</h5>
                    <p class="text-muted small mb-4">Chưa có sản phẩm hoa nào được chọn. Hãy khám phá các mẫu thiết kế tuyệt đẹp nhé.</p>
                    <a href="/products" class="btn btn-primary-custom px-4 py-2.5 rounded-pill text-decoration-none fw-semibold">
                        <i class="fa-solid fa-spa me-1"></i> Khám phá hoa tươi
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .hover-burgundy:hover {
        color: var(--burgundy-primary) !important;
    }
    .hover-danger:hover {
        color: #dc3545 !important;
    }
</style>
@endpush