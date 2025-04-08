@extends('layouts.master')

@section('content')
    <style>
        .order-content {
            padding: 30px;
        }

        .order-detail {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            background-color: #fff;
            margin-bottom: 20px;
        }

        .order-detail h5 {
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* Timeline */
        .order-timeline {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            position: relative;
        }

        .order-timeline::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background-color: #dee2e6;
            z-index: 1;
        }

        .order-timeline .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
            width: 20%;
        }

        .order-timeline .step .icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 5px;
        }

        .order-timeline .step.active .icon {
            background-color: #28a745;
            color: #fff;
        }

        .order-timeline .step .date {
            font-size: 12px;
            color: #666;
        }

        .order-timeline .step.active .date {
            color: #28a745;
        }

        /* Address and Status History */
        .order-info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .order-address,
        .order-status-history {
            width: 48%;
        }

        .order-address h6,
        .order-status-history h6 {
            font-weight: bold;
            margin-bottom: 15px;
        }

        .order-address p,
        .order-status-history p {
            margin-bottom: 10px;
            font-size: 14px;
        }

        /* Product Item */
        .product-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 15px;
        }

        .product-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-right: 15px;
        }

        .product-item .product-info {
            flex: 1;
        }

        .product-item .product-info p {
            margin-bottom: 5px;
        }

        /* Total Price Section */
        .order-total {
            text-align: right;
            margin-top: 20px;
        }

        .order-total p {
            margin-bottom: 10px;
            font-size: 14px;
        }

        .order-total .total {
            font-size: 18px;
            font-weight: bold;
            color: #ff6200;
        }

        .btn-back {
            background-color: #ff6200;
            border-color: #ff6200;
            color: #fff;
            margin-top: 20px;
        }

        .btn-back:hover {
            background-color: #e55a00;
            border-color: #e55a00;
        }

        /* Status Badges */
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 500;
            font-size: 14px;
            display: inline-block;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }

        .status-shipping {
            background-color: #cce5ff;
            color: #004085;
        }

        .status-delivering {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
    </style>

    <div class="container mt-3">
        <div class="row">
            <div class="col-md-12 order-content">
                <div class="order-detail">
                    <h5>Chi Tiết Đơn Hàng #{{ $order->id }}</h5>

                    <!-- Timeline -->
                    <div class="order-timeline">
                        <div class="step {{ in_array($order->status, ['pending', 'paid', 'shipping', 'delivering', 'completed']) ? 'active' : '' }}">
                            <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                            <span>Đơn hàng đã đặt</span>
                            <span class="date">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="step {{ in_array($order->status, ['paid', 'shipping', 'delivering', 'completed']) ? 'active' : '' }}">
                            <div class="icon"><i class="fas fa-check-circle"></i></div>
                            <span>Đã xác nhận</span>
                            <span class="date">{{ $order->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="step {{ in_array($order->status, ['shipping', 'delivering', 'completed']) ? 'active' : '' }}">
                            <div class="icon"><i class="fas fa-truck"></i></div>
                            <span>Đang vận chuyển</span>
                            <span class="date">{{ in_array($order->status, ['shipping', 'delivering', 'completed']) ? $order->updated_at->format('d/m/Y H:i') : '' }}</span>
                        </div>
                        <div class="step {{ $order->status == 'completed' ? 'active' : '' }}">
                            <div class="icon"><i class="fas fa-box-open"></i></div>
                            <span>Đã giao hàng</span>
                            <span class="date">{{ $order->status == 'completed' ? $order->updated_at->format('d/m/Y H:i') : '' }}</span>
                        </div>
                    </div>

                    <div class="order-info-section">
                        <div class="order-address">
                            <h6>Địa Chỉ Nhận Hàng</h6>
                            <p><strong>{{ $order->address->receiver_name ?? 'N/A' }}</strong></p>
                            <p>{{ $order->address->phone ?? 'N/A' }}</p>
                            <p>
                                @if ($order->address)
                                    {{ implode(', ', array_filter([
                                        $order->address->street,
                                        $order->address->ward,
                                        $order->address->district,
                                        $order->address->province,
                                    ])) }}
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                        <div class="order-status-history">
                            <h6>Thông Tin Đơn Hàng</h6>
                            @php
                                $statusTranslations = [
                                    'pending' => 'Chờ Xử Lý',
                                    'paid' => 'Đã Thanh Toán',
                                    'shipping' => 'Đang Vận Chuyển',
                                    'delivering' => 'Đang Giao Hàng',
                                    'completed' => 'Đã Hoàn Thành',
                                    'cancelled' => 'Đã Hủy'
                                ];
                                $translatedStatus = $statusTranslations[strtolower($order->status)] ?? $order->status;
                                
                                $statusClass = 'status-' . strtolower($order->status);
                            @endphp
                            <p><strong>Trạng thái:</strong> <span class="status-badge {{ $statusClass }}">{{ $translatedStatus }}</span></p>
                            <p><strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                            <p><strong>Cập nhật gần nhất:</strong> {{ $order->updated_at->format('d/m/Y H:i:s') }}</p>
                            <p><strong>Phương thức thanh toán:</strong> 
                                @if($order->payment_method == 'cod')
                                    Thanh toán khi nhận hàng (COD)
                                @elseif($order->payment_method == 'bank_transfer')
                                    Chuyển khoản ngân hàng
                                @elseif($order->payment_method == 'momo')
                                    Ví điện tử MoMo
                                @else
                                    {{ $order->payment_method }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="order-products">
                        <h6>Danh Sách Sản Phẩm</h6>
                        @foreach ($order->orderItems as $item)
                            <div class="product-item">
                                <img src="{{ Storage::url($item->product->image) ?? ($item->product->image ?? 'https://via.placeholder.com/80') }}"
                                    alt="Product Image">
                                <div class="product-info">
                                    <p class="mb-1">{{ $item->product->name }}</p>
                                    <p class="mb-1">
                                        Phân loại:
                                        @if ($item->variation)
                                            {{ $item->variation->color->name }} / {{ $item->variation->size->name }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                    <p class="mb-1">Số lượng: {{ $item->quantity }}</p>
                                    <p>Giá: {{ number_format($item->price, 0, ',', '.') }}đ</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="order-total">
                        <p>Thành tiền: {{ number_format($order->total_price - 20000, 0, ',', '.') }}đ</p>
                        <p>Phí vận chuyển: 20.000đ</p>
                        <p class="total">Tổng tiền: {{ number_format($order->total_price, 0, ',', '.') }}đ</p>
                    </div>

                    <div class="mt-4">
                        @if($order->status == 'completed')
                            <button class="btn btn-outline-action">
                                <i class="fas fa-star"></i> Đánh giá sản phẩm
                            </button>
                        @endif
                        
                        {{-- @if(!in_array($order->status, ['completed', 'cancelled']))
                            <form action="{{ route('profile.cancelOrder', $order->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này không?')">
                                    <i class="fas fa-times-circle"></i> Hủy đơn hàng
                                </button>
                            </form>
                        @endif --}}
                        
                        <a href="{{ route('profile.orders') }}" class="btn btn-back">
                            <i class="fas fa-arrow-left"></i> Quay lại danh sách đơn hàng
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection