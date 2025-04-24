@extends('layouts.master')
@section('content')
<style>
    /* General styles */
.shopee-product-grid {
    margin-top: 20px;
}

.shopee-product-card {
    border: none;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.shopee-product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.shopee-product-image {
    height: 220px;
    width: 100%;
    object-fit: cover;
    transition: transform 0.2s ease;
}

.shopee-product-card:hover .shopee-product-image {
    transform: scale(1.05);
}

.shopee-product-title {
    font-size: 0.95rem;
    color: #333;
    height: 2.25rem;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    margin-bottom: 6px;
}

.shopee-price-section {
    margin-bottom: 5px;
}

.shopee-price {
    color: #ee4d2d;
    font-weight: bold;
    font-size: 1rem;
}

.shopee-discount-price {
    font-size: 0.85rem;
}

.shopee-rating {
    font-size: 0.8rem;
    color: #555;
    margin-bottom: 5px;
}

.shopee-buttons .btn {
    font-size: 0.85rem;
    padding: 5px;
    min-width: 44px;
    min-height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
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

.filter-section {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
}

.filter-section h5,
.filter-section h6 {
    color: #333;
}

.category-list .category-item,
.brand-list .brand-item,
.attribute-list .attribute-item {
    display: block;
}

.category-list .category-item.hidden,
.brand-list .brand-item.hidden,
.attribute-list .attribute-item.hidden {
    display: none;
}

.show-more-btn {
    cursor: pointer;
    color: #007bff;
    font-size: 0.9rem;
}

.show-more-btn:hover {
    text-decoration: underline;
}

.price-slider {
    margin: 15px 0;
}

.price-slider input[type="range"] {
    width: 100%;
}

.price-slider .d-flex span {
    font-size: 0.9rem;
    color: #555;
}

.list-group-item {
    border: none;
    padding: 8px 0;
    font-size: 0.9rem;
}

/* Mobile-specific styles */
@media (max-width: 576px) {
    .container {
        padding-left: 10px;
        padding-right: 10px;
    }

    .col-md-3,
    .col-md-9 {
        flex: 0 0 100%;
        max-width: 100%;
    }

    .filter-section {
        padding: 10px;
        margin-bottom: 20px;
    }

    .filter-section h5 {
        font-size: 1.1rem;
    }

    .filter-section h6 {
        font-size: 0.95rem;
    }

    .list-group-item {
        font-size: 0.85rem;
        padding: 6px 0;
    }

    .show-more-btn {
        font-size: 0.8rem;
    }

    .price-slider {
        margin: 10px 0;
    }

    .price-slider .d-flex span {
        font-size: 0.8rem;
    }

    .btn-orange {
        font-size: 0.85rem;
        padding: 8px;
    }

    .shopee-product-grid {
        gap: 0px;
    }

    .shopee-product-card {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .shopee-product-image {
        height: 120px; 
    }

    .shopee-product-title {
        font-size: 0.8rem;
        height: 2rem;
        -webkit-line-clamp: 2;
    }

    .shopee-price {
        font-size: 0.85rem;
    }

    .shopee-discount-price {
        font-size: 0.75rem;
    }

    .shopee-rating {
        font-size: 0.7rem;
    }

    .shopee-buttons .btn {
        font-size: 0.75rem;
        padding: 4px;
        min-width: 36px;
        min-height: 36px;
    }

    .shopee-buttons .btn svg {
        width: 18px;
        height: 18px;
    }

    .favorite-btn {
        min-width: 36px;
        min-height: 36px;
    }

    .d-flex.gap-2 {
        gap: 5px !important;
    }

    .btn-outline-primary.btn-sm {
        font-size: 0.75rem;
        padding: 5px 8px;
    }

    .pagination {
        font-size: 0.85rem;
    }

    .pagination .page-link {
        padding: 5px 10px;
    }
}

/* Tablet-specific styles */
@media (min-width: 576px) and (max-width: 768px) {
    .shopee-product-image {
        height: 180px;
    }

    .shopee-product-title {
        font-size: 0.9rem;
    }

    .shopee-price {
        font-size: 0.95rem;
    }

    .shopee-rating {
        font-size: 0.75rem;
    }

    .shopee-buttons .btn {
        font-size: 0.8rem;
    }
}
</style>
    <div class="container mt-4">
        <div class="row">
            <!-- Filter Section -->
            <div class="col-md-3">
                <div class="filter-section">
                    <h5><strong>BỘ LỌC TÌM KIẾM</strong></h5>
                    <form method="GET" action="{{ route('product.product') }}">
                        <!-- Filter by Category -->
                        <div class="mb-3">
                            <h6>Danh Mục</h6>
                            <ul class="list-group category-list">
                                @foreach ($categories as $index => $category)
                                    <li class="list-group-item category-item {{ $index >= 5 ? 'hidden' : '' }}">
                                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                            {{ in_array($category->id, request()->input('categories', [])) ? 'checked' : '' }}>
                                        {{ $category->name }}
                                    </li>
                                @endforeach
                            </ul>
                            @if ($categories->count() > 5)
                                <small class="show-more-btn d-block mt-2" onclick="toggleItems('category')">Hiện thêm</small>
                            @endif
                        </div>

                        <!-- Filter by Brand -->
                        <div class="mb-3">
                            <h6>Thương Hiệu</h6>
                            <ul class="list-group brand-list">
                                @foreach ($brands as $index => $brand)
                                    <li class="list-group-item brand-item {{ $index >= 5 ? 'hidden' : '' }}">
                                        <input type="checkbox" name="brands[]" value="{{ $brand->id }}"
                                            {{ in_array($brand->id, request()->input('brands', [])) ? 'checked' : '' }}>
                                        {{ $brand->name }}
                                    </li>
                                @endforeach
                            </ul>
                            @if ($brands->count() > 5)
                                <small class="show-more-btn d-block mt-2" onclick="toggleItems('brand')">Hiện thêm</small>
                            @endif
                        </div>

                        <!-- Filter by Price Range (Slider) -->
                        <div class="mb-3">
                            <h6>Khoảng Giá</h6>
                            <div class="price-slider">
                                <input type="range" name="price_min" id="priceMin" min="{{ $minPrice }}"
                                    max="{{ $maxPrice }}" value="{{ request()->input('price_min', $minPrice) }}"
                                    step="10000">
                                <input type="range" name="price_max" id="priceMax" min="{{ $minPrice }}"
                                    max="{{ $maxPrice }}" value="{{ request()->input('price_max', $maxPrice) }}"
                                    step="10000">
                                <div class="d-flex justify-content-between">
                                    <span id="priceMinValue">{{ number_format(request()->input('price_min', $minPrice)) }}
                                        VNĐ</span>
                                    <span id="priceMaxValue">{{ number_format(request()->input('price_max', $maxPrice)) }}
                                        VNĐ</span>
                                </div>
                            </div>
                        </div>

                        <!-- Filter by Stock Availability -->
                        <div class="mb-3">
                            <h6>Tình Trạng</h6>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <input type="checkbox" name="in_stock" value="1"
                                        {{ request()->input('in_stock') == '1' ? 'checked' : '' }}>
                                    Còn hàng
                                </li>
                            </ul>
                        </div>

                        <button type="submit" class="btn btn-orange w-100">Áp Dụng</button>
                    </form>
                </div>
            </div>

            <!-- Product List -->
            <div class="col-md-9">
                <div class="d-flex justify-content-between mb-3 align-items-center flex-wrap">
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-outline-primary btn-sm" onclick="sortProducts('price_asc')">Giá: Thấp đến
                            Cao</button>
                        <button class="btn btn-outline-primary btn-sm" onclick="sortProducts('price_desc')">Giá: Cao đến
                            Thấp</button>
                        <button class="btn btn-outline-primary btn-sm" onclick="sortProducts('newest')">Mới Nhất</button>
                        <button class="btn btn-outline-primary btn-sm" onclick="sortProducts('oldest')">Cũ Nhất</button>
                    </div>
                </div>
                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 shopee-product-grid">
                    @forelse ($products as $product)
                        <div class="col">
                            <div class="card shopee-product-card">
                                <img src="{{ asset('storage/' . $product->image) }}"
                                    class="card-img-top shopee-product-image" alt="{{ $product->name }}">
                                <div class="card-body p-2">
                                    <h6 class="shopee-product-title">{{ $product->name }}</h6>
                                    <div class="shopee-price-section">
                                        <span class="shopee-discount-price me-4 text-danger">-1%</span>
                                        <span class="shopee-price">{{ number_format($product->price) }} VNĐ</span>
                                    </div>
                                    <div class="shopee-rating">
                                        <span><i class="fas fa-star text-warning"></i>
                                            {{ $product->average_rating ?? '4.5' }}</span> |
                                        <span>Đã bán 1k</span>
                                    </div>
                                    <div class="shopee-buttons d-flex align-items-center justify-content-between">
                                        <a href="{{ route('product.show', $product->slug) }}"
                                            class="btn btn-sm btn-orange flex-grow-1 me-1">Mua Ngay</a>
                                        <div class="d-flex flex-grow-1">
                                            <a href="{{ route('product.show', $product->slug) }}"
                                                class="btn btn-sm btn-outline-orange flex-grow-1 me-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="20"
                                                    fill="currentColor" class="bi bi-cart3" viewBox="0 0 16 16">
                                                    <path
                                                        d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l.84 4.479 9.144-.459L13.89 4zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                                                </svg>
                                            </a>
                                            <button
                                                class="btn btn-sm favorite-btn flex-shrink-0 {{ Auth::check() && Auth::user()->favorites->contains($product->id) ? 'favorited' : '' }}"
                                                data-product-id="{{ $product->id }}"
                                                title="{{ Auth::check() && Auth::user()->favorites->contains($product->id) ? 'Bỏ yêu thích' : 'Yêu thích' }}"
                                                aria-label="{{ Auth::check() && Auth::user()->favorites->contains($product->id) ? 'Bỏ yêu thích' : 'Yêu thích' }}">
                                                <i class="fas fa-heart"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center">
                            <p class="no-products-message">Không tìm thấy sản phẩm phù hợp.</p>
                        </div>
                    @endforelse
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>

    <script>
        function toggleItems(type) {
            const hiddenItems = document.querySelectorAll(`.${type}-item.hidden`);
            const showMoreBtn = document.querySelector(`.show-more-btn[onclick="toggleItems('${type}')"]`);

            hiddenItems.forEach(item => {
                item.classList.toggle('hidden');
            });

            if (showMoreBtn.textContent === 'Hiện thêm') {
                showMoreBtn.textContent = 'Ẩn bớt';
            } else {
                showMoreBtn.textContent = 'Hiện thêm';
            }
        }

        function sortProducts(sortType) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', sortType);
            window.location.href = url.toString();
        }

        // Price Slider Functionality
        document.addEventListener('DOMContentLoaded', function () {
            const priceMin = document.getElementById('priceMin');
            const priceMax = document.getElementById('priceMax');
            const priceMinValue = document.getElementById('priceMinValue');
            const priceMaxValue = document.getElementById('priceMaxValue');

            function updatePriceValues() {
                priceMinValue.textContent = Number(priceMin.value).toLocaleString('vi-VN') + ' VNĐ';
                priceMaxValue.textContent = Number(priceMax.value).toLocaleString('vi-VN') + ' VNĐ';
            }

            priceMin.addEventListener('input', updatePriceValues);
            priceMax.addEventListener('input', updatePriceValues);

            // Ensure max is not less than min
            priceMin.addEventListener('change', function () {
                if (parseInt(priceMin.value) > parseInt(priceMax.value)) {
                    priceMax.value = priceMin.value;
                }
                updatePriceValues();
            });

            priceMax.addEventListener('change', function () {
                if (parseInt(priceMax.value) < parseInt(priceMin.value)) {
                    priceMin.value = priceMax.value;
                }
                updatePriceValues();
            });

            // Favorite Button Functionality
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
                        body: JSON.stringify({
                            product_id: productId
                        }),
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                if (data.is_favorited) {
                                    this.classList.add('favorited');
                                    this.title = 'Bỏ yêu thích';
                                    this.setAttribute('aria-label', 'Bỏ yêu thích');
                                    iziToast.success({
                                        title: 'Thành công',
                                        message: data.message,
                                        position: 'topRight'
                                    });
                                } else {
                                    this.classList.remove('favorited');
                                    this.title = 'Yêu thích';
                                    this.setAttribute('aria-label', 'Yêu thích');
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
