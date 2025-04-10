@extends('layouts.master')

@section('content')
    <style>
        .sidebar {
            background-color: #fff;
            padding: 20px 0;
        }

        .sidebar .nav-link {
            color: #333;
            padding: 10px 20px;
            font-weight: 500;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #f0f2f5;
            color: #ff6200;
        }

        .order-content {
            padding: 30px;
        }

        .order-tabs {
            display: flex;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 20px;
        }

        .order-tabs .nav-link {
            padding: 10px 20px;
            color: #333;
            font-weight: 500;
            border-bottom: 2px solid transparent;
        }

        .order-tabs .nav-link:hover,
        .order-tabs .nav-link.active {
            color: #ff6200;
            border-bottom: 2px solid #ff6200;
        }

        .order-item {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 15px;
            padding: 15px;
            background-color: #fff;
        }

        .order-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .order-item-header .status {
            color: #ff6200;
            font-weight: 500;
        }

        .order-item-product {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .order-item-product img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-right: 15px;
        }

        .order-item-product .product-info {
            flex: 1;
        }

        .order-item-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }

        .order-item-footer .total {
            font-size: 16px;
            font-weight: bold;
            color: #ff6200;
        }

        .order-item-footer .actions button {
            margin-left: 10px;
        }

        .btn-action {
            background-color: #ff6200;
            border-color: #ff6200;
            color: #fff;
            padding: 5px 15px;
        }

        .btn-action:hover {
            background-color: #e55a00;
            border-color: #e55a00;
        }

        .btn-outline-action {
            border-color: #ff6200;
            color: #ff6200;
            padding: 5px 15px;
        }

        .btn-outline-action:hover {
            background-color: #ff6200;
            color: #fff;
        }
        .order-summary {
        display: flex;
        flex-direction: column;
        gap: 5px;
        margin: 10px 0;
    }

    .order-summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .order-summary-label {
        font-weight: 500;
        color: #555;
    }

    .order-summary-value {
        font-weight: bold;
    }

    .total-price {
        color: #ff6200;
        font-size: 18px;
    }

    .shipping-fee {
        color: #666;
    }

    .text-orange {
        color: #ff6200;
    }

    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 500;
        font-size: 14px;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-paid {
        background-color: #d4edda;
        color: #155724;
    }

    .status-delivered {
        background-color: #d4edda;
        color: #155724;
    }

    .status-processing {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-completed {
        background-color: #d4edda;
        color: #155724;
    }



    .status-delivering {
        background-color: #e2e3e5;
        color: #383d41;
    }

    .status-cancelled {
        background-color: #f8d7da;
        color: #721c24;
    }
    </style>

<div class="container mt-3">
    <div class="row">
        <!-- Cột bên trái: Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.profile') }}">Thông tin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.address') }}">Địa Chỉ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('favorites') }}">Sản phẩm yêu thích</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('profile.orders') }}">Đơn Mua</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.changePassword') }}">Đổi mật khẩu</a>
                </li>
            </ul>
        </div>

        <!-- Cột bên phải: Danh sách đơn hàng -->
        <div class="col-md-9 col-lg-10 order-content">
            <!-- Thông báo -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Tabs -->
            <div class="order-tabs">
                <a class="nav-link {{ request()->query('status', 'all') == 'all' ? 'active' : '' }}" 
                   href="{{ route('profile.orders', ['status' => 'all']) }}">Tất cả</a>
                <a class="nav-link {{ request()->query('status') == 'pending' ? 'active' : '' }}" 
                   href="{{ route('profile.orders', ['status' => 'pending']) }}">Chờ thanh toán</a>
                <a class="nav-link {{ request()->query('status') == 'delivering' ? 'active' : '' }}" 
                   href="{{ route('profile.orders', ['status' => 'delivering']) }}">Vận chuyển</a>
                <a class="nav-link {{ request()->query('status') == 'delivered' ? 'active' : '' }}" 
                   href="{{ route('profile.orders', ['status' => 'delivered']) }}">Chờ giao hàng</a>
                <a class="nav-link {{ request()->query('status') == 'completed' ? 'active' : '' }}" 
                   href="{{ route('profile.orders', ['status' => 'completed']) }}">Hoàn thành</a>
                <a class="nav-link {{ request()->query('status') == 'cancelled' ? 'active' : '' }}" 
                   href="{{ route('profile.orders', ['status' => 'cancelled']) }}">Đã hủy</a>
                <a class="nav-link {{ request()->query('status') == 'returned' ? 'active' : '' }}" 
                   href="{{ route('profile.orders', ['status' => 'returned']) }}">Trả hàng/Hoàn tiền</a>
            </div>

            <!-- Danh sách đơn hàng -->
            <div class="order-list">
                @forelse ($orders as $order)
                    <div class="order-item">
                        <div class="order-item-header">
                            <div>
                                <a href="/product" class="ml-2 text-decoration-none"><i class="fas fa-store"></i> Xem Thêm sản phẩm</a>
                            </div>
                            <span class="status-badge status-{{ strtolower($order->status) }}">
                                @php
                                    $statusTranslations = [
                                        'pending' => 'Chờ Xử Lý',
                                        'paid' => 'Đã Thanh Toán',
                                        'delivering' => 'Đang Giao Hàng',
                                        'delivered' => 'Đã Giao Hàng',
                                        'completed' => 'Đã Hoàn Thành',
                                        'cancelled' => 'Đã Hủy',
                                        'returned' => 'Trả Hàng/Hoàn Tiền'
                                    ];
                                    echo $statusTranslations[strtolower($order->status)] ?? $order->status;
                                @endphp
                            </span>
                        </div>

                        @foreach ($order->orderItems as $item)
                            <div class="order-item-product">
                                <img src="{{ Storage::url($item->product->image) ?? ($item->product->image ?? 'https://via.placeholder.com/80') }}"
                                    alt="Product Image">
                                <div class="product-info">
                                    {{ $item->product->name }}
                                    <p class="mb-1">
                                        Phân loại:
                                        @if ($item->variation)
                                            {{ $item->variation->color->name }} / {{ $item->variation->size->name }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                    <p class="mb-1">Số lượng: {{ $item->quantity }}</p>
                                </div>
                                <span class="price me-2 text-orange">{{ number_format($item->price, 0, ',', '.') }}đ</span>
                            </div>
                        @endforeach
                        <hr>
                        <div class="order-summary">
                            <div class="order-summary-item">
                                <span class="order-summary-label">Tổng tiền hàng:</span>
                                <span class="order-summary-value">{{ number_format($order->total_price - 20000, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="order-summary-item">
                                <span class="order-summary-label">Phí vận chuyển:</span>
                                <span class="order-summary-value shipping-fee">20.000đ</span>
                            </div>
                            <div class="order-summary-item">
                                <span class="order-summary-label">Tổng thanh toán:</span>
                                <span class="order-summary-value total-price">{{ number_format($order->total_price, 0, ',', '.') }}đ</span>
                            </div>
                        </div>
                        <hr>
                        <div class="actions d-flex flex-wrap align-items-center gap-2">
                            <a href="{{ route('profile.orderDetail', $order->id) }}"
                                class="btn btn-outline-action me-2">
                                <i class="fas fa-info-circle"></i> Xem chi tiết
                            </a>
                            @if (!in_array($order->status, ['completed', 'shipping', 'delivering']))
                                @if (!in_array($order->status, ['completed', 'shipping', 'delivering']) && $order->status != 'cancelled')
                                    <form action="{{ route('profile.cancelOrder', $order->id) }}" method="POST"
                                        class="d-inline me-2">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger"
                                            onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này không?')">
                                            <i class="fas fa-times-circle"></i> Hủy đơn
                                        </button>
                                    </form>
                                @elseif($order->status == 'cancelled')
                                    <span class="text-danger me-2">
                                        <i class="fas fa-ban"></i> Đã hủy
                                    </span>
                                @else
                                    <span class="text-danger me-2">
                                        <i class="fas fa-ban"></i> Không thể hủy
                                    </span>
                                @endif
                            @else
                                <span class="text-danger me-2">
                                    <i class="fas fa-ban"></i> Không thể hủy
                                </span>
                            @endif

                            <a href="{{ route('profile.returnOrder', $order->id) }}" class="btn btn-action">
                                <i class="fas fa-shopping-cart"></i> Mua lại
                            </a>
                            @if ($order->status == 'paid')
                                <button class="btn btn-outline-action ms-2">
                                    <i class="fas fa-envelope"></i> Liên hệ
                                </button>
                                <button class="btn btn-outline-action ms-2">
                                    <i class="fas fa-star"></i> Đánh giá
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <p>Chưa có đơn hàng nào.</p>
                @endforelse
                <div class="d-flex justify-content-center mt-3">
                    {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
