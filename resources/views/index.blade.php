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
                            <img src="https://cf.shopee.vn/file/sg-11134258-7rd3n-m7up6n78yizr66_xxhdpi" class="d-block w-100"
                                alt="Banner 1" style="height: 300px; object-fit: cover;">
                        </div>
                        <div class="carousel-item">
                            <img src="https://cf.shopee.vn/file/vn-11134258-7ras8-m5184szf0klz56_xxhdpi"
                                class="d-block w-100" alt="Banner 2" style="height: 300px; object-fit: cover;">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>

            <!-- Side Banners -->
            <div class="col-md-4">
                <div class="row g-2">
                    <div class="col-12">
                        <img src="https://cf.shopee.vn/file/sg-11134258-7rd3n-m7uh5nilj4lf34_xhdpi" class="w-100 rounded-3"
                            alt="Side Banner 1" style="height: 145px; object-fit: cover;">
                    </div>
                    <div class="col-12">
                        <img src="https://cf.shopee.vn/file/sg-11134258-7rd6a-m7uhc2cdnwgk99_xhdpi" class="w-100 rounded-3"
                            alt="Side Banner 2" style="height: 145px; object-fit: cover;">
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
                                                class="d-block mx-auto mb-1 rounded-circle" alt="{{ $category->name }}"
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

        <!-- Sản Phẩm Nổi Bật -->
        <h2 class="mb-4 text-center text-orange">Sản Phẩm Nổi Bật</h2>
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-3 shophoaily-product-grid">
            @foreach ($products as $product)
                <div class="col">
                    <div class="card shophoaily-product-card">
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top shophoaily-product-image"
                            alt="{{ $product->name }}">
                        <div class="card-body p-2">
                            <h6 class="shophoaily-product-title">{{ $product->name }}</h6>
                            <div class="shophoaily-price-section">
                                <span class="shophoaily-discount-price me-4 text-danger">-1%</span>
                                <span class="shophoaily-price">{{ number_format($product->price) }} VNĐ</span>
                            </div>
                            <div class="shophoaily-rating">
                                <span><i class="fas fa-star text-warning"></i> 4.5</span> |
                                <span>Đã bán 1k</span>
                            </div>
                            <div class="shophoaily-buttons d-flex align-items-center justify-content-between">
                                <a href="{{ route('product.show', $product->slug) }}"
                                    class="btn btn-sm btn-orange flex-grow-1 me-1">Mua Ngay</a>
                                <div class="d-flex flex-grow-1">
                                    <a href="{{ route('product.show', $product->slug) }}" 
                                       class="btn btn-sm btn-outline-orange flex-grow-1 me-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="20" fill="currentColor"
                                             class="bi bi-cart3" viewBox="0 0 16 16">
                                            <path
                                                d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l.84 4.479 9.144-.459L13.89 4zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                                        </svg>
                                    </a>
                                    <button class="btn btn-sm favorite-btn flex-shrink-0 {{ Auth::check() && Auth::user()->favorites->contains($product->id) ? 'favorited' : '' }}"
                                            data-product-id="{{ $product->id }}"
                                            title="{{ Auth::check() && Auth::user()->favorites->contains($product->id) ? 'Bỏ yêu thích' : 'Yêu thích' }}">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                </div>
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

        <!-- Sản Phẩm Theo Danh Mục -->
        @foreach ($categories as $category)
            <div class="mt-5">
                <h3 class="mb-3 text-orange text-center">{{ $category->name }}</h3>
                <p class="text-center">{{ $category->description }}</p>
                <div class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-3 shophoaily-product-grid">
                    @foreach ($categoryProducts[$category->id] as $product)
                        <div class="col">
                            <div class="card shophoaily-product-card">
                                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top shophoaily-product-image"
                                    alt="{{ $product->name }}">
                                <div class="card-body p-2">
                                    <h6 class="shophoaily-product-title">{{ $product->name }}</h6>
                                    <div class="shophoaily-price-section">
                                        <span class="shophoaily-discount-price me-4 text-danger">-1%</span>
                                        <span class="shophoaily-price">{{ number_format($product->price) }} VNĐ</span>
                                    </div>
                                    <div class="shophoaily-rating">
                                        <span><i class="fas fa-star text-warning"></i> 4.5</span> |
                                        <span>Đã bán 1k</span>
                                    </div>
                                    <div class="shophoaily-buttons d-flex align-items-center justify-content-between">
                                        <a href="{{ route('product.show', $product->slug) }}"
                                            class="btn btn-sm btn-orange flex-grow-1 me-1">Mua Ngay</a>
                                        <div class="d-flex flex-grow-1">
                                            <a href="{{ route('product.show', $product->slug) }}" 
                                               class="btn btn-sm btn-outline-orange flex-grow-1 me-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="20" fill="currentColor"
                                                     class="bi bi-cart3" viewBox="0 0 16 16">
                                                    <path
                                                        d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l.84 4.479 9.144-.459L13.89 4zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                                                </svg>
                                            </a>
                                            <button class="btn btn-sm favorite-btn flex-shrink-0 {{ Auth::check() && Auth::user()->favorites->contains($product->id) ? 'favorited' : '' }}"
                                                    data-product-id="{{ $product->id }}"
                                                    title="{{ Auth::check() && Auth::user()->favorites->contains($product->id) ? 'Bỏ yêu thích' : 'Yêu thích' }}">
                                                <i class="fas fa-heart"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
               
            </div>
        @endforeach
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const favoriteButtons = document.querySelectorAll('.favorite-btn');

            favoriteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();

                    const productId = this.getAttribute('data-product-id');

                    fetch('{{ route('favorite.toggle') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ product_id: productId }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (data.is_favorited) {
                                this.classList.add('favorited');
                                this.title = 'Bỏ yêu thích';
                                iziToast.success({
                                    title: 'Thành công',
                                    message: data.message,
                                    position: 'topRight'
                                });
                            } else {
                                this.classList.remove('favorited');
                                this.title = 'Yêu thích';
                                iziToast.info({
                                    title: 'Thông báo',
                                    message: data.message,
                                    position: 'topRight'
                                });
                            }
                        } else {
                            iziToast.error({
                                title: 'Lỗi',
                                message: data.message,
                                position: 'topRight'
                            });
                        }
                    })
                    .catch(error => {
                        iziToast.error({
                            title: 'Lỗi',
                            message: 'Đã xảy ra lỗi, vui lòng thử lại.',
                            position: 'topRight'
                        });
                    });
                });
            });
        });
    </script>
@endsection