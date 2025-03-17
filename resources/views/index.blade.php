@extends('layouts.master')
@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-center text-orange">Sản Phẩm Nổi Bật</h2>

    

    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-3 shophoaily-product-grid">
        @foreach ($products as $product)
        <div class="col">
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
    </div>
    <br>
    <div class="d-flex justify-content-center">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
<style>
    .text-muted {
            display: none;
    }
    .search-bar {
        margin-bottom: 40px;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .search-bar input {
        border-radius: 50px 0 0 50px;
        border: 1px solid #ee4d2d; 
    }

    .search-bar .btn-orange {
        border-radius: 0 50px 50px 0;
        background-color: #ee4d2d;
        color: white;
        border: 1px solid #ee4d2d;
    }

    .search-bar .btn-orange:hover {
        background-color: #d73211;
    }

    .text-orange {
        color: #ee4d2d;
    }

    .shopee-product-grid {
        margin-top: 20px;
    }

    .shopee-product-card {
        border: none;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
        position: relative;
    }

    .shopee-product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .shophoaily-product-image {
        height: 180px;
        object-fit: contain;
        padding: 10px;
        transition: transform 0.2s ease;
    }

    .shophoaily-product-card:hover .shophoaily-product-image {
        transform: scale(1.05);
    }

    .shophoaily-product-title {
        font-size: 0.95rem;
        color: #333;
        height: 2.5rem;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        margin-bottom: 5px;
    }

    .shophoaily-price-section {
        margin-bottom: 5px;
    }

    .shophoaily-price {
        color: #ee4d2d;
        font-weight: bold;
        font-size: 1rem;
    }

    .shophoaily-rating {
        font-size: 0.8rem;
        color: #555;
        margin-bottom: 5px;
    }

    .shophoaily-buttons .btn {
        font-size: 0.85rem;
        padding: 5px;
    }

    .btn-orange {
        background-color: #ee4d2d;
        color: white;
        border: none;
    }

    .btn-orange:hover {
        background-color: #d73211;
    }

    .btn-outline-orange {
        color: #ee4d2d;
        border: 1px solid #ee4d2d;
        background-color: transparent;
    }

    .btn-outline-orange:hover {
        background-color: #ee4d2d;
        color: white;
    }

   

    .no-products-message {
        font-size: 1.2rem;
        color: #ff5722;
        text-align: center;
        margin-top: 20px;
    }
</style>
@endsection