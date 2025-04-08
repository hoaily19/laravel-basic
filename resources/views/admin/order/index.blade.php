@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Danh sách đơn hàng</h2>

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

        <table class="table table-bordered mt-3">
            <thead class="text-center">
                <tr>
                    <th>#</th>
                    <th>Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Phương thức thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Tổng tiền</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if ($order->orderItems && count($order->orderItems) > 0 && $order->orderItems[0]->product && $order->orderItems[0]->product->image)
                                <img src="{{ asset('storage/' . $order->orderItems[0]->product->image) }}" width="50"
                                    alt="{{ $order->orderItems[0]->product->name }}">
                            @else
                                <img src="{{ asset('images/default-product.jpg') }}" width="50" alt="No Image Available">
                            @endif
                        </td>
                        <td>{{ $order->orderItems && count($order->orderItems) > 0 ? $order->orderItems[0]->product->name : 'N/A' }}</td>
                        <td>{{ $order->payment_method }}</td>
                        <td>{{ $order->status }}</td>
                        <td>{{ number_format($order->total_price ?? 0) }} VNĐ</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                data-bs-target="#orderModal" data-order='{{ json_encode($order->toArray()) }}'>
                                <i class="fa-solid fa-eye"></i> Xem chi tiết
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            <div class="pagination">
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">Chi tiết đơn hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="orderDetails">
                        <p><strong>Mã đơn hàng:</strong> <span id="orderId"></span></p>
                        <p><strong>Tên khách hàng:</strong> <span id="userName"></span></p>
                        <p><strong>Địa chỉ: </strong> <span id="userAddress"></span></p>
                        <p><strong>Số điện thoại:</strong> <span id="userPhone"></span></p>
                        <p><strong>Email:</strong> <span id="userEmail"></span></p>
                        <p><strong>Phương thức thanh toán:</strong> <span id="paymentMethod"></span></p>
                        <p><strong>Trạng thái:</strong> <span id="status"></span></p>
                        <p><strong>Phí vận chuyển:</strong> <span id="shippingFee"></span></p>
                        <p><strong>Giảm giá:</strong> <span id="discount"></span></p>
                        <p><strong>Tổng tiền:</strong> <span id="totalPrice"></span></p>
                        <p><strong>Tổng lợi nhuận:</strong> <span id="totalProfit"></span></p>

                        <h6>Danh sách sản phẩm</h6>
                        <table class="table table-bordered">
                            <thead class="text-center">
                                <tr>
                                    <th>#</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Mã sản phẩm</th>
                                    <th>Mã biến thể</th>
                                    <th>Số lượng</th>
                                    <th>Giá bán</th>
                                    <th>Giá gốc</th>
                                    <th>Tổng phụ</th>
                                </tr>
                            </thead>
                            <tbody id="orderItems" class="text-center"></tbody>
                        </table>
                    </div>

                    <form id="updateStatusForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="statusSelect" class="form-label"><strong>Cập nhật trạng thái:</strong></label>
                            <select class="form-select" id="statusSelect" name="status">
                                <option value="pending">Chưa Xử Lí</option>
                                <option value="processing">Đang Xử Lí</option>
                                <option value="delivering">Đang giao</option>
                                <option value="delivered">Đã giao</option>
                                <option value="completed">Hoàn thành</option>
                                <option value="cancelled">Hủy đơn</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('orderModal');
            modal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const order = JSON.parse(button.getAttribute('data-order'));

                document.getElementById('orderModalLabel').textContent = `Chi tiết đơn hàng #${order.id}`;
                document.getElementById('orderId').textContent = order.id;
                document.getElementById('userName').textContent = order.user ? order.user.name : 'N/A';
                document.getElementById('userEmail').textContent = order.user ? order.user.email : 'N/A';
                document.getElementById('userAddress').textContent = order.address ? order.address.street + ', ' + order.address.ward + ', ' + order.address.district + ', ' + order.address.province : 'N/A';
                document.getElementById('userPhone').textContent = order.address ? order.address.phone : 'N/A';
                document.getElementById('paymentMethod').textContent = order.payment_method;
                document.getElementById('status').textContent = order.status;
                document.getElementById('shippingFee').textContent = new Intl.NumberFormat('vi-VN').format(order.shipping_fee || 0) + ' VNĐ';
                document.getElementById('discount').textContent = new Intl.NumberFormat('vi-VN').format(order.discount || 0) + ' VNĐ';
                document.getElementById('totalPrice').textContent = new Intl.NumberFormat('vi-VN').format(order.total_price || 0) + ' VNĐ';

                // Tính tổng lợi nhuận trong JavaScript
                let totalProfit = order.total_price || 0; // Bắt đầu với total_price
                let totalOriginalPrice = 0;
                if (order.order_items && order.order_items.length > 0) {
                    order.order_items.forEach(item => {
                        const originalPrice = item.variation && item.variation.original_price ? item.variation.original_price : (item.product ? item.product.original_price : 0);
                        totalOriginalPrice += originalPrice * item.quantity; // Tổng giá gốc
                    });
                    totalProfit = totalProfit - totalOriginalPrice - 20000; // Trừ tổng giá gốc và phí ship
                }
                document.getElementById('totalProfit').textContent = new Intl.NumberFormat('vi-VN').format(totalProfit) + ' VNĐ';

                // Populate order items
                const itemsTable = document.getElementById('orderItems');
                itemsTable.innerHTML = '';

                if (order.order_items && order.order_items.length > 0) {
                    order.order_items.forEach((item, index) => {
                        const originalPrice = item.variation && item.variation.original_price ? item.variation.original_price : (item.product ? item.product.original_price : 0);
                        const sellingPrice = item.price || 0;

                        const row = `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.product ? item.product.name : 'N/A'}</td> 
                                <td>${item.product_id}</td>
                                <td>${item.variation ? item.variation.color.name : 'N/A'} - ${item.variation ? item.variation.size.name : 'N/A'}</td> 
                                <td>${item.quantity}</td>
                                <td>${new Intl.NumberFormat('vi-VN').format(sellingPrice)} VNĐ</td>
                                <td>${new Intl.NumberFormat('vi-VN').format(originalPrice)} VNĐ</td>
                                <td>${new Intl.NumberFormat('vi-VN').format(item.subtotal || 0)} VNĐ</td>
                            </tr>
                        `;
                        itemsTable.insertAdjacentHTML('beforeend', row);
                    });
                } else {
                    itemsTable.innerHTML = '<tr><td colspan="8" class="text-center">Không có sản phẩm nào</td></tr>';
                }

                const form = document.getElementById('updateStatusForm');
                form.action = `{{ url('admin/order') }}/${order.id}`;
                document.getElementById('statusSelect').value = order.status;
            });
        });
    </script>
@endsection