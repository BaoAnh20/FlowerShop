@extends('layouts.app')

@section('title', 'Tra Cứu Đơn Hàng - BAFlorist')

@section('content')

<div class="container py-5" style="max-width: 580px;">

    <!-- Tiêu đề -->
    <div class="text-center mb-4">
        <h3 class="fw-bold text-primary-custom mb-2" style="font-family: var(--font-heading);">
            Tra cứu đơn hàng
        </h3>
        <p class="text-muted small mb-0">
            Nhập mã đơn hàng và số điện thoại để kiểm tra trạng thái giao nhận.
        </p>
    </div>

    <!-- FORM TRA CỨU -->
    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white" id="lookupFormCard">
        <form id="lookupForm" onsubmit="handleLookup(event)">

            <!-- Thông báo lỗi -->
            <div id="lookupError" class="alert alert-danger small rounded-3 mb-3" style="display: none;">
                <span id="errorText">Không tìm thấy thông tin đơn hàng.</span>
            </div>

            <div class="mb-3">
                <label for="orderCode" class="form-label small fw-semibold text-secondary">Mã đơn hàng *</label>
                <input type="text" id="orderCode" class="form-control rounded-pill px-3 py-2 bg-light border-0 shadow-none small" placeholder="Nhập mã đơn hàng được gửi đến email/số điện thoại" required>
            </div>

            <div class="mb-4">
                <label for="orderPhone" class="form-label small fw-semibold text-secondary">Số điện thoại *</label>
                <input type="tel" id="orderPhone" class="form-control rounded-pill px-3 py-2 bg-light border-0 shadow-none small" placeholder="Nhập số điện thoại đặt hàng" required>
            </div>

            <button type="submit" class="btn btn-primary-custom w-100 py-2.5 rounded-pill fw-bold text-uppercase small shadow-sm">
                Tra cứu ngay
            </button>
        </form>
    </div>

    <!-- KẾT QUẢ TRA CỨU -->
    <div id="lookupResultCard" style="display: none;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-primary-custom mb-0" style="font-family: var(--font-heading);">Kết quả tra cứu</h5>
            <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 small" onclick="resetLookup()">
                Tra cứu lại
            </button>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            
            <!-- Mã đơn & Trạng thái -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 pb-3 border-bottom">
                <div>
                    <small class="text-muted d-block">Mã đơn hàng</small>
                    <strong class="text-primary-custom" id="resCode">BAF-20261018-001</strong>
                    <small class="text-muted d-block mt-0.5">Ngày đặt: 18/10/2026 14:30</small>
                </div>
                <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2 small fw-semibold">
                    Chờ xác nhận
                </span>
            </div>

            <!-- Sản phẩm -->
            <div class="py-3 border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <img src="https://images.unsplash.com/photo-1526047932273-341f2a7631f9?q=80&w=200" 
                         width="55" height="55" class="rounded-3 object-fit-cover shadow-sm border" alt="Hoa">
                    <div class="flex-grow-1">
                        <h6 class="fw-bold text-dark mb-1 small">Bó hồng mẫu đơn Coral Dream</h6>
                        <small class="text-muted">Số lượng: 1</small>
                    </div>
                    <strong class="price-number text-dark small">4.500.000 ₫</strong>
                </div>
            </div>

            <!-- Thông tin nhận hàng -->
            <div class="py-3 border-bottom small text-secondary d-flex flex-column gap-1">
                <div>Người nhận: <strong class="text-dark">Bảo Anh</strong></div>
                <div>Số điện thoại: <strong class="text-dark">0901234567</strong></div>
                <div>Địa chỉ: Quận 1, TP. Hồ Chí Minh</div>
            </div>

            <!-- Tổng tiền -->
            <div class="d-flex justify-content-between align-items-center pt-3">
                <span class="fw-bold text-dark small">Tổng thanh toán</span>
                <strong class="fs-5 text-primary-custom price-number">4.520.000 ₫</strong>
            </div>
        </div>
    </div>

    <!-- Hỗ trợ -->
    <div class="text-center mt-4">
        <small class="text-muted">
            Cần hỗ trợ đơn hàng? 
            <a href="tel:0901234567" class="text-primary-custom fw-semibold text-decoration-underline">Hotline: 0901 234 567</a>
        </small>
    </div>

</div>

@endsection

@push('scripts')
<script>
    function handleLookup(event) {
        event.preventDefault();

        const code = document.getElementById('orderCode').value.trim();
        const phone = document.getElementById('orderPhone').value.trim();

        const errorBox = document.getElementById('lookupError');
        const resultCard = document.getElementById('lookupResultCard');
        const formCard = document.getElementById('lookupFormCard');

        const validCode = "BAF-20261018-001";
        const validPhone = "0901234567";

        if (code === validCode && phone === validPhone) {
            errorBox.style.display = 'none';
            document.getElementById('resCode').innerText = code;

            formCard.style.display = 'none';
            resultCard.style.display = 'block';
        } else {
            errorBox.style.display = 'block';
            document.getElementById('errorText').innerText = 'Không tìm thấy đơn hàng phù hợp. Vui lòng kiểm tra lại mã đơn hàng và số điện thoại.';
        }
    }

    function resetLookup() {
        document.getElementById('lookupFormCard').style.display = 'block';
        document.getElementById('lookupResultCard').style.display = 'none';
        document.getElementById('lookupError').style.display = 'none';

        document.getElementById('orderCode').value = '';
        document.getElementById('orderPhone').value = '';
    }
</script>
@endpush