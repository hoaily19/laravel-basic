@extends('layouts.master')
@section('content')
<div class="container py-4">

    <div class="p-5 mb-6 bg-image rounded-3" style="background-image: url('https://cf.shopee.vn/file/vn-11134258-7ra0g-m7j4miq1e7w675_xxhdpi'); background-size: cover; background-position: top-center;">
        <div class="container-fluid py-5">
            <p class="col-md-8 fs-4 text-white" id="text-container"></p>
            <button class="btn btn-light btn-lg text-danger" type="button">Mua ngay!</button>
        </div>
    </div>
    <br>
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
    <div class="text-center">

        @if (isset($products) && $products->isEmpty())
            <div class="no-products-message">
                <p>Không tìm thấy sản phẩm "{{ request()->input('search') }}".</p>
            </div>
        @endif
    </div>
    <br>
    <div class="d-flex justify-content-center">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection