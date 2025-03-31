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

<div class="container py-4">
    <!-- Slider Section -->
    <div class="row mb-4">
        <!-- Main Slider -->
        <div class="col-md-8">
            <div class="carousel slide rounded-3 shadow-lg" id="heroCarousel" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="https://cf.shopee.vn/file/sg-11134258-7rd3n-m7up6n78yizr66_xxhdpi" 
                             class="d-block w-100" 
                             alt="Banner 1" 
                             style="height: 300px; object-fit: cover;">
                    </div>
                    <div class="carousel-item">
                        <img src="https://cf.shopee.vn/file/vn-11134258-7ras8-m5184szf0klz56_xxhdpi" 
                             class="d-block w-100" 
                             alt="Banner 2" 
                             style="height: 300px; object-fit: cover;">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>

        <!-- Side Banners -->
        <div class="col-md-4">
            <div class="row g-2">
                <div class="col-12">
                    <img src="https://cf.shopee.vn/file/sg-11134258-7rd3n-m7uh5nilj4lf34_xhdpi" 
                         class="w-100 rounded-3" 
                         alt="Side Banner 1" 
                         style="height: 145px; object-fit: cover;">
                </div>
                <div class="col-12">
                    <img src="https://cf.shopee.vn/file/sg-11134258-7rd6a-m7uhc2cdnwgk99_xhdpi" 
                         class="w-100 rounded-3" 
                         alt="Side Banner 2" 
                         style="height: 145px; object-fit: cover;">
                </div>
            </div>
        </div>
    </div>

    <!-- Category Menu -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="shopee-categories-bar bg-light py-3 rounded-3">
                <div class="container">
                    <div class="d-flex flex-wrap justify-content-center">
                        @if (isset($categories) && $categories->isNotEmpty())
                            @foreach ($categories as $category)
                                <div class="category-item position-relative text-center mx-3 my-2">
                                    <a href="{{ route('product.product', ['category' => $category->id]) }}" 
                                       class="text-decoration-none text-dark">
                                        <img src="{{ asset('storage/' . $category->image) }}" 
                                             class="d-block mx-auto mb-1 rounded-circle" 
                                             alt="{{ $category->name }}" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                        <small class="d-block" style="font-size: 12px;">{{ $category->name }}</small>
                                    </a>
                                    @if ($category->brands->isNotEmpty())
                                        <div class="brand-dropdown position-absolute bg-white shadow-sm rounded-3 p-2" 
                                             style="display: none; z-index: 10; min-width: 150px; top: 100%; left: 50%; transform: translateX(-50%);">
                                            @foreach ($category->brands as $brand)
                                                <a href="{{ route('product.product', ['category' => $category->id, 'brand' => $brand->id]) }}" 
                                                   class="d-block text-decoration-none text-dark py-1 px-2 hover-bg-light">
                                                    {{ $brand->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-center text-muted">Không có danh mục nào để hiển thị.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h2 class="mb-4 text-center text-orange">Sản Phẩm Nổi Bật</h2>

    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-3 shophoaily-product-grid">
        @foreach ($products as $product)
        <div class="col">
            <div class="card shophoaily-product-card">
                <img src="{{ asset('storage/' . $product->image) }}" 
                     class="card-img-top shophoaily-product-image" 
                     alt="{{ $product->name }}">
                <div class="card-body p-2">
                    <h6 class="shophoaily-product-title">{{ $product->name }}</h6>
                    <div class="shophoaily-price-section">
                        <span class="shophoaily-price">{{ number_format($product->price) }} VNĐ</span>
                    </div>
                    <div class="shophoaily-rating">
                        <span><i class="fas fa-star text-warning"></i> 4.5</span> | 
                        <span>Đã bán 1k</span>
                    </div>
                    <div class="shophoaily-buttons">
                        <a href="{{ route('product.show', $product->slug) }}" 
                           class="btn btn-sm btn-orange w-100 mb-1">Mua Ngay</a>
                        <a href="#" 
                           class="btn btn-sm btn-outline-orange w-100">Thêm Vào Giỏ</a>
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

    <div class="d-flex justify-content-center mt-4">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
/* CSS cho Category Menu */
.shopee-categories-bar {
    background-color: #f8f9fa;
}

.category-item {
    transition: all 0.3s ease;
}

.category-item:hover {
    transform: translateY(-5px);
}

.category-item:hover .brand-dropdown {
    display: block !important;
}

.brand-dropdown {
    display: none;
}

.brand-dropdown a:hover {
    background-color: #f1f1f1;
    border-radius: 5px;
}
</style>

@endsection