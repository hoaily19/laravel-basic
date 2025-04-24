@extends('layouts.master')

@section('content')
    @if (session('success'))
        <script>
            iziToast.success({
                title: 'Thành công',
                message: '{{ session('success') }}',
                position: 'topRight'
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            iziToast.error({
                title: 'Lỗi',
                message: '{{ session('error') }}',
                position: 'topRight'
            });
        </script>
    @endif

    <style>
        /* General styles */
.text-orange {
    color: #ee4d2d;
}

.shopee-cart-card {
    border: none;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.shopee-cart-header {
    font-size: 0.95rem;
    color: #333;
    font-weight: 500;
}

.shopee-cart-item {
    font-size: 0.9rem;
}

.shopee-cart-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 4px;
}

.shopee-cart-product-name {
    font-size: 0.95rem;
    color: #333;
    line-height: 1.3;
}

.shopee-cart-price,
.shopee-cart-total {
    font-size: 1rem;
    font-weight: bold;
}

.shopee-cart-quantity .input-group {
    width: 120px;
    margin: 0 auto;
}

.shopee-cart-quantity .form-control {
    font-size: 0.9rem;
    padding: 4px;
    width: 50px;
}

.shopee-cart-quantity .btn {
    font-size: 0.9rem;
    padding: 4px 8px;
}

.shopee-cart-delete {
    font-size: 0.9rem;
}

.shopee-cart-footer {
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.shopee-cart-footer label,
.shopee-cart-footer strong {
    font-size: 0.95rem;
}

.shopee-cart-delete-selected {
    font-size: 0.95rem;
}

.btn-orange {
    background-color: #ee4d2d;
    color: white;
    border: none;
    padding: 8px 16px;
    font-size: 0.95rem;
}

.btn-orange:hover {
    background-color: #d73211;
}

.alert-info {
    font-size: 0.95rem;
}

/* Mobile-specific styles */
@media (max-width: 576px) {
    .container {
        padding-left: 10px;
        padding-right: 10px;
    }

    .my-4 {
        margin-top: 1.5rem !important;
        margin-bottom: 1.5rem !important;
    }

    h2 {
        font-size: 1.5rem;
    }

    .shopee-cart-card {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .shopee-cart-header {
        display: none; /* Hide header labels on mobile */
    }

    .shopee-cart-item {
        flex-direction: column;
        align-items: flex-start;
        padding: 10px 0;
        font-size: 0.85rem;
    }

    .shopee-cart-item > div {
        margin-bottom: 10px;
        width: 100%;
    }

    .shopee-cart-image {
        width: 60px;
        height: 60px;
    }

    .shopee-cart-product-name {
        font-size: 0.9rem;
    }

    .shopee-cart-price,
    .shopee-cart-total {
        font-size: 0.9rem;
    }

    .shopee-cart-quantity .input-group {
        width: 100px;
    }

    .shopee-cart-quantity .form-control {
        font-size: 0.85rem;
        width: 40px;
    }

    .shopee-cart-quantity .btn {
        font-size: 0.85rem;
        padding: 2px 6px;
    }

    .shopee-cart-delete {
        font-size: 0.85rem;
    }

    .shopee-cart-footer {
        padding: 10px;
    }

    .shopee-cart-footer label,
    .shopee-cart-footer strong {
        font-size: 0.9rem;
    }

    .shopee-cart-delete-selected {
        font-size: 0.9rem;
    }

    .btn-orange {
        font-size: 0.9rem;
        padding: 6px 12px;
        width: 100%;
    }

    .alert-info {
        font-size: 0.9rem;
        padding: 10px;
    }

    .shopee-cart-footer .d-flex {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px !important;
    }

    .shopee-cart-footer .d-flex > div {
        width: 100%;
    }

    .cart-item-checkbox {
        transform: scale(1.2);
    }
}

/* Tablet-specific styles */
@media (min-width: 576px) and (max-width: 768px) {
    .shopee-cart-image {
        width: 70px;
        height: 70px;
    }

    .shopee-cart-product-name {
        font-size: 0.9rem;
    }

    .shopee-cart-price,
    .shopee-cart-total {
        font-size: 0.95rem;
    }

    .shopee-cart-quantity .input-group {
        width: 110px;
    }

    .shopee-cart-quantity .form-control {
        font-size: 0.85rem;
    }
}/* General styles */
.text-orange {
    color: #ee4d2d;
}

.shopee-cart-card {
    border: none;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.shopee-cart-header {
    font-size: 0.95rem;
    color: #333;
    font-weight: 500;
}

.shopee-cart-item {
    font-size: 0.9rem;
}

.shopee-cart-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 4px;
}

.shopee-cart-product-name {
    font-size: 0.95rem;
    color: #333;
    line-height: 1.3;
}

.shopee-cart-price,
.shopee-cart-total {
    font-size: 1rem;
    font-weight: bold;
}

.shopee-cart-quantity .input-group {
    width: 120px;
    margin: 0 auto;
}

.shopee-cart-quantity .form-control {
    font-size: 0.9rem;
    padding: 4px;
    width: 50px;
}

.shopee-cart-quantity .btn {
    font-size: 0.9rem;
    padding: 4px 8px;
}

.shopee-cart-delete {
    font-size: 0.9rem;
}

.shopee-cart-footer {
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.shopee-cart-footer label,
.shopee-cart-footer strong {
    font-size: 0.95rem;
}

.shopee-cart-delete-selected {
    font-size: 0.95rem;
}

.btn-orange {
    background-color: #ee4d2d;
    color: white;
    border: none;
    padding: 8px 16px;
    font-size: 0.95rem;
}

.btn-orange:hover {
    background-color: #d73211;
}

.alert-info {
    font-size: 0.95rem;
}

/* Mobile-specific styles */
@media (max-width: 576px) {
    .container {
        padding-left: 10px;
        padding-right: 10px;
    }

    .my-4 {
        margin-top: 1.5rem !important;
        margin-bottom: 1.5rem !important;
    }

    h2 {
        font-size: 1.5rem;
    }

    .shopee-cart-card {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .shopee-cart-header {
        display: none; /* Hide header labels on mobile */
    }

    .shopee-cart-item {
        flex-direction: column;
        align-items: flex-start;
        padding: 10px 0;
        font-size: 0.85rem;
    }

    .shopee-cart-item > div {
        margin-bottom: 10px;
        width: 100%;
    }

    .shopee-cart-image {
        width: 60px;
        height: 60px;
    }

    .shopee-cart-product-name {
        font-size: 0.9rem;
    }

    .shopee-cart-price,
    .shopee-cart-total {
        font-size: 0.9rem;
    }

    .shopee-cart-quantity .input-group {
        width: 100px;
    }

    .shopee-cart-quantity .form-control {
        font-size: 0.85rem;
        width: 40px;
    }

    .shopee-cart-quantity .btn {
        font-size: 0.85rem;
        padding: 2px 6px;
    }

    .shopee-cart-delete {
        font-size: 0.85rem;
    }

    .shopee-cart-footer {
        padding: 10px;
    }

    .shopee-cart-footer label,
    .shopee-cart-footer strong {
        font-size: 0.9rem;
    }

    .shopee-cart-delete-selected {
        font-size: 0.9rem;
    }

    .btn-orange {
        font-size: 0.9rem;
        padding: 6px 12px;
        width: 100%;
    }

    .alert-info {
        font-size: 0.9rem;
        padding: 10px;
    }

    .shopee-cart-footer .d-flex {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px !important;
    }

    .shopee-cart-footer .d-flex > div {
        width: 100%;
    }

    .cart-item-checkbox {
        transform: scale(1.2);
    }
}

/* Tablet-specific styles */
@media (min-width: 576px) and (max-width: 768px) {
    .shopee-cart-image {
        width: 70px;
        height: 70px;
    }

    .shopee-cart-product-name {
        font-size: 0.9rem;
    }

    .shopee-cart-price,
    .shopee-cart-total {
        font-size: 0.95rem;
    }

    .shopee-cart-quantity .input-group {
        width: 110px;
    }

    .shopee-cart-quantity .form-control {
        font-size: 0.85rem;
    }
}
    </style>
    <div class="container my-4">
        <h2 class="mb-4 text-orange">Giỏ Hàng</h2>

        @if ($carts->isEmpty())
            <div class="alert alert-info text-center">
                Giỏ hàng của bạn đang trống. <a href="{{ route('product.index') }}" class="text-orange">Mua sắm ngay!</a>
            </div>
        @else
            <div class="card shopee-cart-card">
                <div class="card-header bg-light">
                    <div class="row align-items-center shopee-cart-header">
                        <div class="col-1 col-md-1">
                            <input type="checkbox" id="select-all" onclick="toggleSelectAll()">
                        </div>
                        <div class="col-4 col-md-4">Sản Phẩm</div>
                        <div class="col-2 col-md-2 text-center">Đơn Giá</div>
                        <div class="col-2 col-md-2 text-center">Số Lượng</div>
                        <div class="col-2 col-md-2 text-center">Số Tiền</div>
                        <div class="col-1 col-md-1 text-center">Thao Tác</div>
                    </div>
                </div>
                <div class="card-body">
                    @foreach ($carts as $cart)
                        <div class="row align-items-center py-3 border-bottom shopee-cart-item">
                            <div class="col-12 col-md-1 mb-2 mb-md-0">
                                <input type="checkbox" name="selected_items[]" value="{{ $cart->id }}"
                                    class="cart-item-checkbox" onclick="updateTotal()" form="cart-form">
                            </div>
                            <div class="col-12 col-md-4 mb-2 mb-md-0">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $cart->product->image ? Storage::url($cart->product->image) : 'https://via.placeholder.com/60' }}"
                                        alt="{{ $cart->product->name }}" class="me-3 shopee-cart-image">
                                    <div>
                                        <p class="mb-1 shopee-cart-product-name">{{ $cart->product->name }}</p>
                                        @if ($cart->variation)
                                            <small class="text-muted">
                                                Phân loại:
                                                @if ($cart->variation->size)
                                                    {{ $cart->variation->size->name }}
                                                @endif
                                                @if ($cart->variation->color)
                                                    , {{ $cart->variation->color->name }}
                                                @endif
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-2 text-center mb-2 mb-md-0">
                                <span class="text-danger shopee-cart-price">đ{{ number_format($cart->price, 0) }}</span>
                            </div>
                            <div class="col-6 col-md-2 text-center mb-2 mb-md-0">
                                <div class="input-group shopee-cart-quantity">
                                    <a href="{{ route('cart.update', ['id' => $cart->id, 'change' => -1]) }}"
                                        class="btn btn-outline-secondary">-</a>
                                    <form action="{{ route('cart.update.quantity', $cart->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <input type="number" name="quantity" class="form-control text-center"
                                            value="{{ $cart->quantity }}" min="1"
                                            max="{{ $cart->variation ? $cart->variation->stock : $cart->product->stock }}"
                                            onchange="this.form.submit()">
                                    </form>
                                    <a href="{{ route('cart.update', ['id' => $cart->id, 'change' => 1]) }}"
                                        class="btn btn-outline-secondary">+</a>
                                </div>
                            </div>
                            <div class="col-6 col-md-2 text-center mb-2 mb-md-0">
                                <span class="text-danger shopee-cart-total">đ{{ number_format($cart->price * $cart->quantity, 0) }}</span>
                            </div>
                            <div class="col-6 col-md-1 text-center">
                                <a href="{{ route('cart.delete', $cart->id) }}" class="text-danger shopee-cart-delete"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')">Xóa</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <form action="{{ route('checkout.index') }}" method="GET" id="cart-form">
                @csrf
                <div class="card mt-3 shopee-cart-footer">
                    <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                        <div class="d-flex align-items-center flex-wrap gap-3">
                            <div>
                                <input type="checkbox" id="select-all-footer" onclick="toggleSelectAll()">
                                <label for="select-all-footer" class="ms-2">Chọn Tất Cả ({{ $carts->count() }})</label>
                            </div>
                            <a href="#" class="text-danger shopee-cart-delete-selected" onclick="deleteSelectedItems()">Xóa</a>
                        </div>
                        <div class="d-flex align-items-center flex-wrap gap-3 mt-2 mt-md-0">
                            <div>
                                <strong>Tổng Thanh Toán (<span id="selected-count">0</span> Sản phẩm):</strong>
                                <span class="text-danger h5">đ<span id="total-price">0</span></span>
                            </div>
                            <button type="submit" class="btn btn-orange">Mua Hàng</button>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>

    <script>
        function toggleSelectAll() {
            const selectAll = document.getElementById('select-all');
            const selectAllFooter = document.getElementById('select-all-footer');
            const checkboxes = document.querySelectorAll('.cart-item-checkbox');

            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });

            selectAllFooter.checked = selectAll.checked;
            updateTotal();
        }

        function updateTotal() {
            const checkboxes = document.querySelectorAll('.cart-item-checkbox:checked');
            let total = 0;
            let selectedCount = checkboxes.length;

            checkboxes.forEach(checkbox => {
                const cartItem = checkbox.closest('.shopee-cart-item');
                const price = parseFloat(cartItem.querySelector('.shopee-cart-price').textContent.replace('đ', '').replace(/,/g, ''));
                const quantity = parseInt(cartItem.querySelector('.form-control').value);
                total += price * quantity;
            });

            document.getElementById('selected-count').textContent = selectedCount;
            document.getElementById('total-price').textContent = number_format(total, 0);
        }

        function deleteSelectedItems() {
            const checkboxes = document.querySelectorAll('.cart-item-checkbox:checked');
            if (checkboxes.length === 0) {
                iziToast.warning({
                    title: 'Cảnh báo',
                    message: 'Vui lòng chọn ít nhất một sản phẩm để xóa!',
                    position: 'topRight'
                });
                return;
            }

            if (!confirm('Bạn có chắc chắn muốn xóa các sản phẩm đã chọn?')) {
                return;
            }

            const form = document.getElementById('cart-form');
            form.action = '{{ route('cart.delete.selected') }}';
            form.method = 'POST';
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);
            form.submit();
        }

        function number_format(number, decimals = 0, dec_point = '.', thousands_sep = ',') {
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            const n = !isFinite(+number) ? 0 : +number;
            const prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
            const sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep;
            const dec = (typeof dec_point === 'undefined') ? '.' : dec_point;
            let s = '';
            const toFixedFix = function (n, prec) {
                const k = Math.pow(10, prec);
                return Math.round(n * k) / k;
            };
            s = (prec ? toFixedFix(n, prec) : Math.round(n)).toString().split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }
    </script>
@endsection