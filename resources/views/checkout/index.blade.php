@extends('layouts.app')

@section('title', 'Thanh Toán Đơn Hàng - BAFlorist')

@section('content')
<div class="container py-5" style="max-width: 720px;">
    
    <!-- Toast đặt thành công -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1080;">
        <div id="successToast" class="toast align-items-center text-white border-0 shadow-lg rounded-pill px-3 py-2" role="alert" aria-live="assertive" aria-atomic="true" style="background-color: var(--burgundy-primary); display: none;">
            <div class="d-flex align-items-center">
                <div class="toast-body fw-semibold small">
                    <i class="fa-solid fa-circle-check me-2"></i> Đã đặt hàng thành công!
                </div>
            </div>
        </div>
    </div>

    <!-- KHỐI 1: FORM NHẬP THÔNG TIN  -->
    <div id="checkoutFormContainer">
        <div class="p-3 mb-4 rounded-4 shadow-sm border-0" style="background-color: var(--burgundy-subtle); color: var(--burgundy-dark);">
            <div class="d-flex align-items-start gap-2 small">
                <i class="fa-solid fa-circle-info mt-0.5 text-warning"></i>
                <div>
                    <strong>BAFlorist giao hoa tại TP. Hồ Chí Minh.</strong><br>
                    Chúng tôi sẽ liên hệ lại quý khách để xác nhận thông tin đơn hàng sau khi nhận thanh toán.
                </div>
            </div>
        </div>

        <form action="javascript:void(0);" method="POST" id="checkoutForm" onsubmit="handleCheckout(event)">
            @csrf

            <!-- 1. SẢN PHẨM -->
            <div class="glass-blur-card p-4 mb-4 shadow-sm rounded-4 bg-white border-0">
                <h5 class="fw-bold mb-3 text-primary-custom" style="font-family: var(--font-heading);">Sản phẩm</h5>
                
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 pb-3">
                    <div class="d-flex align-items-center gap-3">
                        <img src="https://images.unsplash.com/photo-1526047932273-341f2a7631f9?q=80&w=200" 
                             class="rounded-3 object-fit-cover border shadow-sm" style="width: 65px; height: 65px;" alt="Hoa">
                        <div>
                            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">Bó hồng mẫu đơn Coral Dream</h6>
                            <div class="d-flex align-items-center gap-2 text-muted small">
                                <span>SL: <strong class="text-dark" id="productQuantity">1</strong></span>
                                <div class="btn-group btn-group-sm ms-2">
                                    <button type="button" class="btn btn-outline-secondary py-0 px-1 border-0 bg-light" onclick="decreaseQuantity()">-</button>
                                    <button type="button" class="btn btn-outline-secondary py-0 px-1 border-0 bg-light" onclick="increaseQuantity()">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="fw-bold price-number text-dark fs-5">4.500.000 ₫</span>
                </div>

                <!-- Nội dung thiệp & Yêu cầu riêng-->
                <div class="d-flex align-items-center gap-2 pt-2 border-top flex-wrap">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1.5 small fw-semibold text-dark border" onclick="toggleSection('cardMessageSection')">
                        <i class="fa-solid fa-plus text-primary-custom me-1"></i> Nội dung thiệp
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-1.5 small fw-semibold text-dark border" onclick="toggleSection('specialRequestSection')">
                        <i class="fa-solid fa-plus text-primary-custom me-1"></i> Yêu cầu riêng
                    </button>
                </div>

                <!--Nội dung thiệp -->
                <div id="cardMessageSection" class="mt-3" style="display: none;">
                    <label class="form-label small text-muted fw-semibold">Nội dung thiệp chúc mừng</label>
                    <textarea name="card_message" class="form-control rounded-4 p-3 bg-light border-0 shadow-none small" rows="2" placeholder="Lời chúc ngọt ngào gửi đến người nhận..."></textarea>
                </div>

                <!--Yêu cầu riêng -->
                <div id="specialRequestSection" class="mt-3" style="display: none;">
                    <label class="form-label small text-muted fw-semibold">Yêu cầu riêng / Ghi chú cho tiệm hoa</label>
                    <textarea name="note" class="form-control rounded-4 p-3 bg-light border-0 shadow-none small" rows="2" placeholder="Ví dụ: Giao hoa cẩn thận, gọi trước khi tới..."></textarea>
                </div>
            </div>

            <!-- 2. THỜI GIAN GIAO  -->
            <div class="glass-blur-card p-4 mb-4 shadow-sm rounded-4 bg-white border-0">
                <h5 class="fw-bold mb-3 text-primary-custom" style="font-family: var(--font-heading);">Thời gian giao hoa</h5>
                
                <div class="mb-3">
                    <label class="form-label small text-muted fw-semibold mb-2">Chọn ngày giao <span class="text-danger">*</span></label>
                    <div class="row g-2">
                        <div class="col-4">
                            <input type="radio" class="btn-check" name="delivery_date" id="today" autocomplete="off" checked>
                            <label class="btn btn-outline-burgundy w-100 rounded-pill py-2 small fw-semibold" for="today">Hôm nay</label>
                        </div>
                        <div class="col-4">
                            <input type="radio" class="btn-check" name="delivery_date" id="tomorrow" autocomplete="off">
                            <label class="btn btn-outline-burgundy w-100 rounded-pill py-2 small fw-semibold" for="tomorrow">Ngày mai</label>
                        </div>
                        <div class="col-4">
                            <input type="radio" class="btn-check" name="delivery_date" id="other_day" autocomplete="off">
                            <label class="btn btn-outline-burgundy w-100 rounded-pill py-2 small fw-semibold" for="other_day">Ngày khác</label>
                        </div>
                    </div>
                </div>

                <div class="mb-0">
                    <label class="form-label small text-muted fw-semibold mb-2">Khung giờ giao cụ thể <span class="text-danger">*</span></label>
                    <select name="delivery_time" id="deliveryTimeSelect" class="form-select rounded-pill px-3 py-2 bg-light border-0 shadow-none small" required>
                        <option value="Càng sớm càng tốt (1-2 tiếng)" id="optAsSoonAsPossible">Càng sớm càng tốt (Trong 1 - 2 tiếng)</option>
                        <option value="Sáng (08:00 - 11:30)">Buổi sáng (08:00 - 11:30)</option>
                        <option value="Chiều (13:30 - 17:30)" selected>Buổi chiều (13:30 - 17:30)</option>
                        <option value="Tối (18:00 - 20:30)">Buổi tối (18:00 - 20:30)</option>
                    </select>
                </div>
            </div>

            <!-- 3. NGƯỜI ĐẶT -->
            <div class="glass-blur-card p-4 mb-4 shadow-sm rounded-4 bg-white border-0">
                <h5 class="fw-bold mb-3 text-primary-custom" style="font-family: var(--font-heading);">Người đặt</h5>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="sender_name" id="senderName" class="form-control rounded-pill px-3 py-2.5 bg-light border-0 shadow-none small" placeholder="Tên người đặt *" value="{{ Auth::user()->name ?? '' }}" required>
                    </div>
                    <div class="col-md-6">
                        <input type="tel" name="sender_phone" id="senderPhone" class="form-control rounded-pill px-3 py-2.5 bg-light border-0 shadow-none small" placeholder="Số điện thoại *" value="{{ Auth::user()->phone ?? '' }}" required>
                    </div>
                </div>
                <div class="mt-2 text-muted small fst-italic">
                    Chúng tôi sẽ gửi hình ảnh thành phẩm trước khi giao.
                </div>
            </div>

            <!-- 4. NGƯỜI NHẬN -->
            <div class="glass-blur-card p-4 mb-4 shadow-sm rounded-4 bg-white border-0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-primary-custom" style="font-family: var(--font-heading);">Người nhận</h5>
                    <div class="form-check m-0">
                        <input class="form-check-input custom-checkbox shadow-none" type="checkbox" id="sameAsSender" onchange="copySenderInfo(this)">
                        <label class="form-check-label small fw-semibold cursor-pointer text-primary-custom" for="sameAsSender">Là người đặt</label>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <input type="text" name="recipient_name" id="receiverName" class="form-control rounded-pill px-3 py-2.5 bg-light border-0 shadow-none small" placeholder="Tên người nhận *" required>
                    </div>
                    <div class="col-md-6">
                        <input type="tel" name="recipient_phone" id="receiverPhone" class="form-control rounded-pill px-3 py-2.5 bg-light border-0 shadow-none small" placeholder="Số điện thoại người nhận *" required>
                    </div>
                </div>

                <input type="hidden" name="city" value="TP. Hồ Chí Minh">

                <div class="mb-0">
                    <textarea name="address" class="form-control rounded-4 p-3 bg-light border-0 shadow-none small" rows="2" placeholder="Địa chỉ giao hàng: số nhà, đường, phường, quận *" required></textarea>
                </div>
            </div>

            <!-- 5. PHÍ GIAO HÀNG -->
            <div class="glass-blur-card p-4 mb-4 shadow-sm rounded-4 bg-white border-0">
                <h5 class="fw-bold mb-3 text-primary-custom" style="font-family: var(--font-heading);">Phí giao hàng</h5>
                
                <div class="d-flex flex-column gap-2.5 small">
                    <div class="form-check">
                        <input class="form-check-input custom-checkbox" type="radio" name="shipping_fee_option" id="ship_zone1" value="20000" checked>
                        <label class="form-check-label text-dark cursor-pointer" for="ship_zone1">
                            Quận 1, Quận 3, Quận 5, Quận 10: <strong class="price-number">20.000 ₫</strong>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input custom-checkbox" type="radio" name="shipping_fee_option" id="ship_zone2" value="30000">
                        <label class="form-check-label text-dark cursor-pointer" for="ship_zone2">
                            Quận 4, Phú Nhuận: <strong class="price-number">30.000 ₫</strong>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input custom-checkbox" type="radio" name="shipping_fee_option" id="ship_zone3" value="40000">
                        <label class="form-check-label text-dark cursor-pointer" for="ship_zone3">
                            Quận 6, Quận 7, Quận 8, Quận 11: <strong class="price-number">40.000 ₫</strong>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input custom-checkbox" type="radio" name="shipping_fee_option" id="ship_zone4" value="40000">
                        <label class="form-check-label text-dark cursor-pointer" for="ship_zone4">
                            Bình Thạnh, Quận 8: <strong class="price-number">40.000 ₫</strong>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input custom-checkbox" type="radio" name="shipping_fee_option" id="ship_zone5" value="60000">
                        <label class="form-check-label text-dark cursor-pointer" for="ship_zone5">
                            Tân Bình | Bình Tân | Tân Phú | Gò Vấp: <strong class="price-number">60.000 ₫</strong>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input custom-checkbox" type="radio" name="shipping_fee_option" id="ship_zone6" value="50000">
                        <label class="form-check-label text-dark cursor-pointer" for="ship_zone6">
                            Quận 2: <strong class="price-number">50.000 ₫</strong>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input custom-checkbox" type="radio" name="shipping_fee_option" id="ship_zone7" value="80000">
                        <label class="form-check-label text-dark cursor-pointer" for="ship_zone7">
                            Quận 9 - Quận 12 - Nhà Bè - Bình Chánh - Hóc Môn - Thủ Đức: <strong class="price-number">80.000 ₫</strong>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input custom-checkbox" type="radio" name="shipping_fee_option" id="ship_pickup" value="0">
                        <label class="form-check-label text-dark fw-bold cursor-pointer text-primary-custom" for="ship_pickup">
                            Lấy tại cửa hàng
                        </label>
                    </div>
                </div>
            </div>

            <!-- 6. TỔNG - THANH TOÁN - QR -->
            <div class="glass-blur-card p-4 mb-4 shadow-sm rounded-4 bg-white border-0">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold text-dark fs-5">Tổng cộng</span>
                    <span class="price-number text-primary-custom fs-3 fw-bold">4.500.000 ₫</span>
                </div>
                <p class="text-muted small mb-4">
                    Tạm tính · Chúng tôi sẽ báo <a href="#" class="text-decoration-underline text-primary-custom">phí phụ thu</a> và ưu đãi (nếu có) khi liên hệ
                </p>

                <!-- Phương thức thanh toán -->
                <input type="hidden" name="payment_method" value="banking_full">
                
                <div class="p-3 mb-4 rounded-3 bg-light border-0 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-qrcode text-primary-custom fs-4"></i>
                    <div>
<div class="small fw-bold text-dark">Thanh toán ngay</div>
<div class="text-muted" style="font-size: 0.85rem;">
    Hoàn tất thanh toán để xác nhận đơn hàng
</div>
                    </div>
                </div>

                <!-- KHUNG QR -->
                <div id="qrCodeContainer" class="p-4 mb-4 rounded-4 bg-light border text-center" style="border-style: dashed !important; border-color: var(--burgundy-primary) !important;">
                    <h6 class="fw-bold text-primary-custom mb-2" style="font-family: var(--font-heading);">Quét mã QR để chuyển khoản 100%</h6>
                    <p class="text-muted small mb-3">Sử dụng ứng dụng Ngân hàng hoặc MoMo để quét mã bên dưới</p>
                    
                    <!-- Hình QR  -->
                    <div class="bg-white p-3 rounded-4 d-inline-block shadow-sm border mb-3">
                        <img src="{{ asset('images/image.png') }}" 
                             alt="QR Chuyển Khoản" class="img-fluid rounded-2" style="max-width: 180px; height: auto;" id="qrImage">
                    </div>

                    <div class="small text-secondary bg-white p-3 rounded-3 text-start border-0 shadow-sm">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Ngân hàng:</span>
                            <strong class="text-dark">BIDV</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Số tài khoản:</span>
                            <strong class="text-dark">1700xxx</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Chủ tài khoản:</span>
                            <strong class="text-dark">SHOP HOA TUOI BAFLORIST</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Số tiền chuyển khoản:</span>
                            <strong class="text-danger price-number">4.500.000 ₫</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Nội dung chuyển khoản:</span>
                            <strong class="text-primary-custom">BAF [Số điện thoại đặt hàng]</strong>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary-custom w-100 py-3 fw-bold text-uppercase fs-6 shadow-sm rounded-pill">
                    Đặt hàng
                </button>
            </div>
        </form>
    </div>

    <!-- KHỐI 2: THÔNG BÁO HOÀN TẤT (sau khi đặt thành công) -->
    <div id="successCompleteContainer" class="glass-blur-card p-5 shadow-sm rounded-4 bg-white border-0 text-center" style="display: none;">
        <div class="mb-3">
            <span class="fa-stack fa-2x text-primary-custom">
                <i class="fa-solid fa-circle fa-stack-2x" style="color: var(--burgundy-subtle);"></i>
                <i class="fa-solid fa-check fa-stack-1x" style="color: var(--burgundy-primary);"></i>
            </span>
        </div>
        <h3 class="fw-bold text-primary-custom mb-2" style="font-family: var(--font-heading);">Đặt Hàng Thành Công!</h3>
        <p class="text-muted small mb-4 mx-auto" style="max-width: 400px;">
            Cảm ơn bạn đã tin tưởng lựa chọn BAFlorist. Tiệm hoa sẽ xác nhận thanh toán và liên hệ giao đơn hàng trong thời gian sớm nhất.
        </p>

        <div class="d-flex align-items-center justify-content-center gap-3 flex-wrap">
            <a href="{{ route('products.index') }}" class="btn btn-primary-custom rounded-pill px-4 py-2 fw-semibold small text-decoration-none text-white shadow-sm d-inline-flex align-items-center justify-content-center" style="height: 44px;">
                <i class="fa-solid fa-spa me-2"></i> Xem thêm các sản phẩm khác
            </a>
            <a href="{{ route('home') }}" class="btn btn-outline-dark rounded-pill px-4 py-2 fw-semibold small text-decoration-none d-inline-flex align-items-center justify-content-center" style="height: 44px; border-color: #ced4da;">
                <i class="fa-solid fa-house me-2"></i> Về trang chủ
            </a>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    let qty = 1;
    function increaseQuantity() {
        qty++;
        document.getElementById('productQuantity').innerText = qty;
    }
    function decreaseQuantity() {
        if (qty > 1) {
            qty--;
            document.getElementById('productQuantity').innerText = qty;
        }
    }

    function toggleSection(sectionId) {
        const el = document.getElementById(sectionId);
        if (el.style.display === 'none' || el.style.display === '') {
            el.style.display = 'block';
        } else {
            el.style.display = 'none';
        }
    }

    function copySenderInfo(checkbox) {
        const senderName = document.getElementById('senderName').value;
        const senderPhone = document.getElementById('senderPhone').value;
        
        const receiverName = document.getElementById('receiverName');
        const receiverPhone = document.getElementById('receiverPhone');

        if (checkbox.checked) {
            receiverName.value = senderName;
            receiverPhone.value = senderPhone;
        } else {
            receiverName.value = '';
            receiverPhone.value = '';
        }
    }

    const radioToday = document.getElementById('today');
    const radioTomorrow = document.getElementById('tomorrow');
    const radioOtherDay = document.getElementById('other_day');
    const optAsSoonAsPossible = document.getElementById('optAsSoonAsPossible');
    const deliveryTimeSelect = document.getElementById('deliveryTimeSelect');

    function updateDeliveryTimeOptions() {
        if (radioToday.checked) {
            optAsSoonAsPossible.style.display = 'block';
            optAsSoonAsPossible.disabled = false;
        } else {
            optAsSoonAsPossible.style.display = 'none';
            optAsSoonAsPossible.disabled = true;
            if (deliveryTimeSelect.value === "Càng sớm càng tốt (1-2 tiếng)") {
                deliveryTimeSelect.value = "Chiều (13:30 - 17:30)";
            }
        }
    }

    if(radioToday && radioTomorrow && radioOtherDay) {
        radioToday.addEventListener('change', updateDeliveryTimeOptions);
        radioTomorrow.addEventListener('change', updateDeliveryTimeOptions);
        radioOtherDay.addEventListener('change', updateDeliveryTimeOptions);
    }

    // Hàm xử lý khi bấm Đặt: hiện toast, ẩn form nhập, hiện khung hoàn tất
    function handleCheckout(event) {
        event.preventDefault(); // Giữ nguyên trang, không redirect

        const form = document.getElementById('checkoutForm');
        if (form.checkValidity()) {
            // Toast
            const toastEl = document.getElementById('successToast');
            toastEl.style.display = 'block';

            // Ẩn form nhập thông tin đơn hàng
            document.getElementById('checkoutFormContainer').style.display = 'none';

            // Hiện khung xác nhận hoàn tất & nút xem sản phẩm khác
            document.getElementById('successCompleteContainer').style.display = 'block';

            // Cuộn lên đầu trang để khách hàng thấy thông báo hoàn tất
            window.scrollTo({ top: 0, behavior: 'smooth' });

            // Tự động tắt Toast sau 3 giây
            setTimeout(function() {
                toastEl.style.display = 'none';
            }, 3000);
        } else {
            form.reportValidity();
        }
    }
</script>
@endpush