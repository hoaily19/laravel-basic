@extends('layouts.master')

@section('content')
    <style>
        /* Modern step indicator */
        .steps {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 800px;
            margin: 30px auto;
            position: relative;
        }

        .steps::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 3px;
            background: #e0e0e0;
            z-index: 1;
        }

        .step {
            text-align: center;
            position: relative;
            z-index: 2;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e0e0e0;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
            border: 3px solid #fff;
        }

        .step.active .step-circle {
            background: #ee4d2d;
        }

        .step.completed .step-circle {
            background: #ee4d2d;
        }

        .step.completed .step-circle::before {
            content: '\f00c';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
        }

        .step-label {
            font-size: 14px;
            color: #757575;
            font-weight: 500;
        }

        .step.active .step-label,
        .step.completed .step-label {
            color: #ee4d2d;
        }

        /* Checkout form styling */
        .checkout-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .address-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .address-card:hover {
            border-color: #ee4d2d;
        }

        .address-card.selected {
            border-color: #ee4d2d;
            background-color: #f8f9fa;
        }

        .address-card .name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .address-card .phone {
            color: #757575;
            margin-bottom: 5px;
        }

        .address-card .address {
            color: #616161;
        }

        .payment-method {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .payment-method:hover {
            border-color: #ee4d2d;
        }

        .payment-method.selected {
            border-color: #ee4d2d;
            background-color: #f8f9fa;
        }

        .payment-method img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            margin-right: 15px;
        }

        .payment-method .details {
            flex: 1;
        }

        .payment-method .title {
            font-weight: 600;
            margin-bottom: 3px;
        }

        .payment-method .description {
            color: #757575;
            font-size: 14px;
        }

        .order-summary-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .order-summary-item:last-child {
            border-bottom: none;
        }

        .order-summary-item .label {
            color: #616161;
        }

        .order-summary-item .value {
            font-weight: 500;
        }

        .order-summary-item.total {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .btn-checkout {
            background: #ee4d2d;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 8px;
            width: 100%;
            transition: all 0.3s;
        }

        .btn-checkout:hover {
            background: #ee4d2d;
            transform: translateY(-2px);
        }

        .coupon-input {
            display: flex;
            margin-bottom: 15px;
        }

        .coupon-input input {
            flex: 1;
            border: 1px solid #e0e0e0;
            border-radius: 8px 0 0 8px;
            padding: 10px 15px;
            outline: none;
        }

        .coupon-input button {
            background: #ee4d2d;
            color: white;
            border: none;
            padding: 0 20px;
            border-radius: 0 8px 8px 0;
            cursor: pointer;
            transition: all 0.3s;
        }

        .coupon-input button:hover {
            background: #a7361f;
        }

        .coupon-message {
            margin-top: 5px;
            font-size: 14px;
        }

        .coupon-message.success {
            color: #ee4d2d;
        }

        .coupon-message.error {
            color: #f44336;
        }

        .product-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 15px;
        }

        .product-item .details {
            flex: 1;
        }

        .product-item .name {
            font-weight: 500;
            margin-bottom: 5px;
        }

        .product-item .price {
            color: #ee4d2d;
            font-weight: 600;
        }

        .product-item .quantity {
            color: #757575;
            font-size: 14px;
        }
    </style>

    <div class="container">
        <!-- Step indicator -->
        <div class="steps">
            <div class="step completed">
                <div class="step-circle">1</div>
                <div class="step-label">Giỏ hàng</div>
            </div>
            <div class="step active">
                <div class="step-circle">2</div>
                <div class="step-label">Thanh toán</div>
            </div>
            <div class="step">
                <div class="step-circle">3</div>
                <div class="step-label">Hoàn tất</div>
            </div>
        </div>

        <div class="row">
            <!-- Customer Information -->
            <div class="col-lg-7">
                <div class="checkout-container">
                    <h3 class="section-title">Thông tin giao hàng</h3>

                    @if (!empty($addresses) && count($addresses) > 0)
                        <div id="existing-addresses">
                            @foreach ($addresses as $address)
                                <div class="address-card {{ $loop->first ? 'selected' : '' }}"
                                    data-address-id="{{ $address->id }}">
                                    <div class="name">{{ $address->name }}</div>
                                    <div class="phone">{{ $address->phone }}</div>
                                    <div class="address">
                                        {{ $address->street }}, {{ $address->ward }}, {{ $address->district }},
                                        {{ $address->province }}
                                    </div>
                                    <input type="radio" name="address_id_temp" value="{{ $address->id }}"
                                        {{ $loop->first ? 'checked' : '' }} style="display: none;">
                                </div>
                            @endforeach

                            <button type="button" class="btn btn-link p-0" id="toggle-address-form">
                                <i class="fas fa-plus"></i> Thêm địa chỉ mới
                            </button>
                        </div>
                    @else
                        <div class="text-center py-3">Bạn chưa có địa chỉ nào. Vui lòng thêm địa chỉ mới.</div>
                    @endif

                    <form action="{{ route('profile.storeAddress') }}" method="POST" id="new-address-form"
                        style="display: {{ empty($addresses) || count($addresses) == 0 ? 'block' : 'none' }};"
                        class="mt-4">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" name="receiver_name" placeholder="Nhập họ và tên"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" name="phone" placeholder="Nhập số điện thoại"
                                    required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <select class="form-select" id="province" name="province" required>
                                        <option value="">Tỉnh/Thành phố</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <select class="form-select" id="district" name="district" required>
                                        <option value="">Quận/Huyện</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <select class="form-select" id="ward" name="ward" required>
                                        <option value="">Xã/Phường</option>
                                    </select>
                                </div>
                            </div>
                            <input type="text" class="form-control" id="street" name="street"
                                placeholder="Số nhà, tên đường" required>
                        </div>

                        <input type="hidden" name="user_id" value="{{ optional(Auth::user())->id }}">
                        <input type="hidden" name="address" id="full_address">

                        <button type="submit" class="btn btn-dark">
                            <i class="fas fa-save"></i> Lưu địa chỉ
                        </button>
                    </form>
                </div>

                <div class="checkout-container mt-4">
                    <h3 class="section-title">Phương thức thanh toán</h3>

                    <form action="{{ route('orders.store') }}" method="POST" id="checkout-form">
                        @csrf
                        <input type="hidden" name="address_id" id="address_id"
                            value="{{ $addresses->first()->id ?? '' }}">
                        <input type="hidden" name="shipping_fee" id="shipping_fee" value="20000">
                        <input type="hidden" name="total_amount" id="total_amount"
                            value="{{ $carts->sum(fn($cart) => $cart->price * $cart->quantity) + 20000 }}">
                        <input type="hidden" name="discount" id="discount" value="0">

                        <div class="payment-method selected" onclick="selectPaymentMethod(this, 'cod')">
                            <input type="radio" name="payment_method" id="cod" value="cod" checked
                                style="display: none;">
                            <img src="https://cdn-icons-png.flaticon.com/512/2897/2897832.png" alt="COD">
                            <div class="details">
                                <div class="title">Thanh toán khi nhận hàng (COD)</div>
                                <div class="description">Thanh toán bằng tiền mặt khi nhận hàng</div>
                            </div>
                            <i class="fas fa-check-circle text-success"></i>
                        </div>

                        <div class="payment-method" onclick="selectPaymentMethod(this, 'vnpay')">
                            <input type="radio" name="payment_method" id="vnpay" value="vnpay"
                                style="display: none;">
                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTp1v7T287-ikP1m7dEUbs2n1SbbLEqkMd1ZA&s"
                                alt="VNPay">
                            <div class="details">
                                <div class="title">VNPay</div>
                                <div class="description">Thanh toán qua cổng VNPay</div>
                            </div>
                            <i class="fas fa-check-circle text-success" style="opacity: 0;"></i>
                        </div>

                        <div class="payment-method" onclick="selectPaymentMethod(this, 'momo')">
                            <input type="radio" name="payment_method" id="momo" value="momo"
                                style="display: none;">
                            <img src="https://play-lh.googleusercontent.com/uCtnppeJ9ENYdJaSL5av-ZL1ZM1f3b35u9k8EOEjK3ZdyG509_2osbXGH5qzXVmoFv0"
                                alt="MOMO">
                            <div class="details">
                                <div class="title">Ví MoMo</div>
                                <div class="description">Thanh toán qua ví điện tử MoMo</div>
                            </div>
                            <i class="fas fa-check-circle text-success" style="opacity: 0;"></i>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-5">
                <div class="checkout-container">
                    <h3 class="section-title">Đơn hàng của bạn</h3>

                    <div class="mb-3">
                        @forelse ($carts as $cart)
                            <div class="product-item">
                                <img src="{{ $cart->product->image ? Storage::url($cart->product->image) : $cart->product->image_url ?? 'https://via.placeholder.com/80' }}"
                                    alt="{{ $cart->product->name }}">
                                <div class="details">
                                    <div class="name">{{ $cart->product->name }}</div>
                                    <div class="quantity">Số lượng: {{ $cart->quantity }}</div>
                                </div>
                                <div class="price">{{ number_format($cart->price * $cart->quantity) }}₫</div>
                            </div>
                        @empty
                            <div class="text-center py-3">Không có sản phẩm nào trong giỏ hàng</div>
                        @endforelse
                    </div>

                    <div class="mb-3">
                        <div class="coupon-input">
                            <input type="text" id="coupon_code" placeholder="Nhập mã giảm giá">
                            <button type="button" id="apply_coupon">
                                <i class="fas fa-tag"></i> Áp dụng
                            </button>
                        </div>
                        <div id="coupon_message" class="coupon-message"></div>
                    </div>

                    <div class="order-summary">
                        <div class="order-summary-item">
                            <span class="label">Tạm tính</span>
                            <span class="value"
                                id="subtotal_display">{{ number_format($carts->sum(fn($cart) => $cart->price * $cart->quantity)) }}₫</span>
                        </div>
                        <div class="order-summary-item">
                            <span class="label">Phí vận chuyển</span>
                            <span class="value" id="shipping_fee_display">20.000₫</span>
                        </div>
                        <div class="order-summary-item">
                            <span class="label">Giảm giá</span>
                            <span class="value" id="discount_display">0₫</span>
                        </div>
                        <div class="order-summary-item total">
                            <span class="label">Tổng cộng</span>
                            <span class="value"
                                id="total_amount_display">{{ $carts->sum(fn($cart) => $cart->price * $cart->quantity) + 20000 }}₫</span>
                        </div>
                    </div>

                    <button type="submit" form="checkout-form" class="btn-checkout mt-3">
                        <i class="fas fa-shopping-bag"></i> Đặt hàng
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", async function() {
            document.querySelectorAll('.address-card').forEach(card => {
                card.addEventListener('click', function() {
                    const addressId = this.getAttribute('data-address-id');
                    selectAddress(this, addressId);
                });
            });

            function selectAddress(element, addressId) {
                console.log('Selecting address:', addressId);
                document.querySelectorAll('.address-card').forEach(card => {
                    card.classList.remove('selected');
                });
                element.classList.add('selected');
                const addressInput = document.getElementById('address_id');
                if (addressInput) {
                    addressInput.value = addressId;
                }
            }

            function selectPaymentMethod(element, method) {
                document.querySelectorAll('.payment-method').forEach(item => {
                    item.classList.remove('selected');
                    item.querySelector('.fa-check-circle').style.opacity = '0';
                });

                element.classList.add('selected');
                element.querySelector('.fa-check-circle').style.opacity = '1';

                const radio = element.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                } else {
                    const radioId = method; // cod, vnpay, momo
                    const radioElement = document.getElementById(radioId);
                    if (radioElement) {
                        radioElement.checked = true;
                    }
                }
            }

            document.querySelectorAll('.payment-method').forEach(method => {
                method.addEventListener('click', function() {
                    const radio = this.querySelector('input[type="radio"]');
                    if (radio) {
                        selectPaymentMethod(this, radio.id);
                    }
                });
            });

            const toggleAddressFormBtn = document.getElementById("toggle-address-form");
            const newAddressForm = document.getElementById("new-address-form");
            let isFormVisible = {{ empty($addresses) || count($addresses) == 0 ? 'true' : 'false' }};
            console.log("isFormVisible:", isFormVisible);
            if (toggleAddressFormBtn) {
                toggleAddressFormBtn.addEventListener("click", function() {
                    if (isFormVisible) {
                        newAddressForm.style.display = "none";
                        toggleAddressFormBtn.innerHTML = '<i class="fas fa-plus"></i> Thêm địa chỉ mới';
                    } else {
                        newAddressForm.style.display = "block";
                        toggleAddressFormBtn.innerHTML = '<i class="fas fa-minus"></i> Ẩn form';
                        document.querySelectorAll('.address-card').forEach(card => {
                            card.classList.remove('selected');
                        });
                        document.getElementById('address_id').value = "";
                    }
                    isFormVisible = !isFormVisible;
                });
            }

            async function fetchData(url) {
                try {
                    const response = await fetch(url);
                    return await response.json();
                } catch (error) {
                    console.error("Lỗi khi gọi API:", error);
                    return null;
                }
            }

            async function loadProvinces() {
                const data = await fetchData("https://provinces.open-api.vn/api/p/");
                if (data) {
                    const provinceSelect = document.getElementById("province");
                    data.forEach(province => {
                        const option = document.createElement("option");
                        option.value = province.name;
                        option.text = province.name;
                        provinceSelect.appendChild(option);
                    });
                }
            }

            async function loadDistricts(provinceName) {
                const districtSelect = document.getElementById("district");
                const wardSelect = document.getElementById("ward");

                districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                wardSelect.innerHTML = '<option value="">Chọn xã/phường</option>';

                if (!provinceName) return;

                const provinces = await fetchData("https://provinces.open-api.vn/api/p/");
                const province = provinces?.find(p => p.name === provinceName);

                if (province) {
                    const data = await fetchData(
                        `https://provinces.open-api.vn/api/p/${province.code}?depth=2`);
                    if (data) {
                        data.districts.forEach(district => {
                            const option = document.createElement("option");
                            option.value = district.name;
                            option.text = district.name;
                            districtSelect.appendChild(option);
                        });
                    }
                }
            }

            async function loadWards(districtName, provinceName) {
                const wardSelect = document.getElementById("ward");
                wardSelect.innerHTML = '<option value="">Chọn xã/phường</option>';

                if (!districtName || !provinceName) return;

                const provinces = await fetchData("https://provinces.open-api.vn/api/p/");
                const province = provinces?.find(p => p.name === provinceName);

                if (province) {
                    const data = await fetchData(
                        `https://provinces.open-api.vn/api/p/${province.code}?depth=2`);
                    const district = data?.districts.find(d => d.name === districtName);

                    if (district) {
                        const wardData = await fetchData(
                            `https://provinces.open-api.vn/api/d/${district.code}?depth=2`);
                        if (wardData) {
                            wardData.wards.forEach(ward => {
                                const option = document.createElement("option");
                                option.value = ward.name;
                                option.text = ward.name;
                                wardSelect.appendChild(option);
                            });
                        }
                    }
                }
            }

            // Coupon 
            const applyCouponBtn = document.getElementById("apply_coupon");
            const couponCodeInput = document.getElementById("coupon_code");
            const couponMessage = document.getElementById("coupon_message");
            const totalAmountDisplay = document.getElementById("total_amount_display");
            const subtotalDisplay = document.getElementById("subtotal_display");
            const discountDisplay = document.getElementById("discount_display");
            const shippingFeeDisplay = document.getElementById("shipping_fee_display");
            const shippingFeeInput = document.getElementById("shipping_fee");
            const totalAmountInput = document.getElementById("total_amount");
            const discountInput = document.getElementById("discount");

            let shippingFee = parseInt(shippingFeeInput.value);
            let originalSubtotal = {{ $carts->sum(fn($cart) => $cart->price * $cart->quantity) }};
            let totalAmount = parseInt(totalAmountInput.value);

            applyCouponBtn.addEventListener("click", async function() {
                const couponCode = couponCodeInput.value.trim();
                if (!couponCode) {
                    couponMessage.textContent = "Vui lòng nhập mã giảm giá!";
                    couponMessage.className = "coupon-message error";
                    return;
                }

                try {
                    const response = await fetch("{{ route('coupon.apply') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": document.querySelector(
                                'meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            coupon_code: couponCode,
                            total_amount: originalSubtotal
                        })
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(result.message || 'Lỗi không xác định');
                    }

                    if (result.success) {
                        couponMessage.className = "coupon-message success";
                        couponMessage.textContent = result.message;

                        const newTotal = result.new_total + shippingFee;
                        totalAmountDisplay.textContent = newTotal.toLocaleString() + "₫";
                        discountDisplay.textContent = "-" + result.discount.toLocaleString() + "₫";
                        totalAmountInput.value = newTotal;
                        discountInput.value = result.discount;
                    } else {
                        couponMessage.className = "coupon-message error";
                        couponMessage.textContent = result.message;
                    }
                } catch (error) {
                    console.error('Error:', error);
                    couponMessage.className = "coupon-message error";
                    couponMessage.textContent = error.message || "Đã xảy ra lỗi, vui lòng thử lại!";
                }
            });

            document.getElementById("province").addEventListener("change", async function() {
                await loadDistricts(this.value);
            });

            document.getElementById("district").addEventListener("change", async function() {
                await loadWards(this.value, document.getElementById("province").value);
            });

            await loadProvinces();

            document.getElementById("new-address-form").addEventListener("submit", function(e) {
                const province = document.getElementById("province").value;
                const district = document.getElementById("district").value;
                const ward = document.getElementById("ward").value;
                const street = document.getElementById("street").value;

                const fullAddress = `${street}, ${ward}, ${district}, ${province}`;
                document.getElementById("full_address").value = fullAddress;
            });
        });
    </script>
@endsection
