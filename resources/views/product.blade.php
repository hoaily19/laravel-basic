@extends('layouts.master')
@section('content')
    <style>
        .product-card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-image {
            height: 220px;
            object-fit: cover;
        }

        .price {
            color: red;
            font-weight: bold;
        }
    </style>

    <div class="container mt-4">
        <div class="row">
            <!-- Bộ lọc -->
            <div class="col-md-2">
                <h5><strong>BỘ LỌC TÌM KIẾM</strong></h5>
                <ul class="list-group">
                    <li class="list-group-item"><input type="checkbox"> Đồ lẻ (101k+)</li>
                    <li class="list-group-item"><input type="checkbox"> Thời Trang Trẻ Em (28k+)</li>
                    <li class="list-group-item"><input type="checkbox"> Chân váy (10k+)</li>
                    <li class="list-group-item"><input type="checkbox"> Đầm/Váy (9k+)</li>
                </ul>
            </div>

            <!-- Danh sách sản phẩm -->
            <div class="col-md-10">
                <div class="d-flex justify-content-between mb-3">
                    <h5>Kết quả tìm kiếm cho từ khóa 'set áo váy'</h5>
                    <select class="form-select w-auto">
                        <option>Liên Quan</option>
                        <option>Mới Nhất</option>
                        <option>Bán Chạy</option>
                        <option>Giá</option>
                    </select>
                </div>
                <div class="row">
                    <!-- Sản phẩm -->
                    @foreach ($products as $product)
                    <div class="col-md-3 mb-4">
                        <div class="card shophoaily-product-card">
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top shophoaily-product-image" alt="{{ $product->name }}">
                            <div class="card-body p-2">
                                <h6 class="shophoaily-product-title">{{ $product->name }}</h6>
                                <div class="shophoaily-price-section">
                                    <span class="shophoaily-price">{{ number_format($product->price) }} VNĐ</span>
                                </div>
                                <div class="shophoaily-rating">
                                    <span><i class="fas fa-star text-warning"></i> 4.5</span> | <span>Đã bán 1k</span>
                                </div>
                                <div class="shophoaily-buttons">
                                    <a href="{{ route('product.show', $product->slug) }}" class="btn btn-sm btn-orange w-100 mb-1">Mua Ngay</a>
                                    <a href="#" class="btn btn-sm btn-outline-orange w-100">Thêm Vào Giỏ</a>
                                </div>
                            </div>
                        </div>
                       
                    </div>
                    @endforeach
                    <!-- Thêm nhiều sản phẩm tương tự -->
                </div>
                <div class="d-flex justify-content-center">
                    {{ $products->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
