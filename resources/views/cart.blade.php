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
    <div class="container my-4">
        <h2 class="mb-4">Giỏ Hàng</h2>


        @if ($carts->isEmpty())
            <div class="alert alert-info">
                Giỏ hàng của bạn đang trống. <a href="{{ route('product.index') }}">Mua sắm ngay!</a>
            </div>
        @else
            <div class="card">
                <div class="card-header bg-light">
                    <div class="row align-items-center">
                        <div class="col-1">
                            <input type="checkbox" id="select-all" onclick="toggleSelectAll()">
                        </div>
                        <div class="col-4">Sản Phẩm</div>
                        <div class="col-2 text-center">Đơn Giá</div>
                        <div class="col-2 text-center">Số Lượng</div>
                        <div class="col-2 text-center">Số Tiền</div>
                        <div class="col-1 text-center">Thao Tác</div>
                    </div>
                </div>
                <div class="card-body">
                    @foreach ($carts as $cart)
                        <div class="row align-items-center py-3 border-bottom">
                            <div class="col-1">
                                <input type="checkbox" name="selected_items[]" value="{{ $cart->id }}"
                                    class="cart-item-checkbox" onclick="updateTotal()" form="cart-form">
                            </div>
                            <div class="col-4">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $cart->product->image ? Storage::url($cart->product->image) : 'https://via.placeholder.com/80' }}"
                                        alt="{{ $cart->product->name }}" class="me-3"
                                        style="width: 80px; height: 80px; object-fit: cover;">
                                    <div>
                                        <p class="mb-1">{{ $cart->product->name }}</p>
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
                            <div class="col-2 text-center">
                                <span class="text-danger">đ{{ number_format($cart->price, 0) }}</span>
                            </div>
                            <div class="col-2 text-center">
                                <div class="input-group" style="width: auto; margin: 0 auto;">
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
                            <div class="col-2 text-center">
                                <span class="text-danger">đ{{ number_format($cart->price * $cart->quantity, 0) }}</span>
                            </div>
                            <div class="col-1 text-center">
                                <a href="{{ route('cart.delete', $cart->id) }}" class="text-danger"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')">Xóa</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <form action="{{ route('checkout.index') }}" method="GET" id="cart-form">
                @csrf
                <div class="card mt-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <input type="checkbox" id="select-all-footer" onclick="toggleSelectAll()">
                            <label for="select-all-footer" class="ms-2">Chọn Tất Cả ({{ $carts->count() }})</label>
                            <a href="#" class="ms-3 text-danger" onclick="deleteSelectedItems()">Xóa</a>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <strong>Tổng Thanh Toán (<span id="selected-count">0</span> Sản phẩm):</strong>
                                <span class="text-danger h5">đ<span id="total-price">0</span></span>
                            </div>
                            <button type="submit" class="btn btn-danger btn-lg">Mua Hàng</button>
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
                const cartItem = checkbox.closest('.row');
                const price = parseFloat(cartItem.querySelector('.col-2:nth-child(3) span').textContent.replace('đ',
                    '').replace(/,/g, ''));
                const quantity = parseInt(cartItem.querySelector('.form-control').value);
                total += price * quantity;
            });

            document.getElementById('selected-count').textContent = selectedCount;
            document.getElementById('total-price').textContent = number_format(total, 0);
        }

        function deleteSelectedItems() {
            const checkboxes = document.querySelectorAll('.cart-item-checkbox:checked');
            if (checkboxes.length === 0) {
                alert('Vui lòng chọn ít nhất một sản phẩm để xóa!');
                return;
            }

            if (!confirm('Bạn có chắc chắn muốn xóa các sản phẩm đã chọn?')) {
                return;
            }

            const form = document.getElementById('cart-form');
            form.action = '{{ route('cart.delete.selected') }}';
            form.submit();
        }

        function number_format(number, decimals = 0, dec_point = '.', thousands_sep = ',') {
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            const n = !isFinite(+number) ? 0 : +number;
            const prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
            const sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep;
            const dec = (typeof dec_point === 'undefined') ? '.' : dec_point;
            let s = '';
            const toFixedFix = function(n, prec) {
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
