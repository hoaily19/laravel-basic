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

    .filter-section {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
    }

    .category-list .category-item,
    .brand-list .brand-item {
        display: block;
    }

    .category-list .category-item.hidden,
    .brand-list .brand-item.hidden {
        display: none;
    }

    .show-more-btn {
        cursor: pointer;
        color: #007bff;
    }

    .show-more-btn:hover {
        text-decoration: underline;
    }
</style>

<div class="container mt-4">
    <div class="row">
        <!-- Bộ lọc -->
        <div class="col-md-3">
            <div class="filter-section">
                <h5><strong>BỘ LỌC TÌM KIẾM</strong></h5>
                <form method="GET" action="{{ route('product.product') }}">
                    <!-- Lọc theo danh mục -->
                    <div class="mb-3">
                        <h6>Danh Mục</h6>
                        <ul class="list-group category-list">
                            @foreach ($categories as $index => $category)
                            <li class="list-group-item category-item {{ $index >= 5 ? 'hidden' : '' }}">
                                <input type="checkbox" 
                                       name="categories[]" 
                                       value="{{ $category->id }}" 
                                       {{ in_array($category->id, request()->input('categories', [])) ? 'checked' : '' }}>
                                {{ $category->name }}
                            </li>
                            @endforeach
                        </ul>
                        @if ($categories->count() > 5)
                        <small class="show-more-btn d-block mt-2" onclick="toggleItems('category')">Hiện thêm</small>
                        @endif
                    </div>

                    <!-- Lọc theo thương hiệu -->
                    <div class="mb-3">
                        <h6>Thương Hiệu</h6>
                        <ul class="list-group brand-list">
                            @foreach ($brands as $index => $brand)
                            <li class="list-group-item brand-item {{ $index >= 5 ? 'hidden' : '' }}">
                                <input type="checkbox" 
                                       name="brands[]" 
                                       value="{{ $brand->id }}" 
                                       {{ in_array($brand->id, request()->input('brands', [])) ? 'checked' : '' }}>
                                {{ $brand->name }}
                            </li>
                            @endforeach
                        </ul>
                        @if ($brands->count() > 5)
                        <small class="show-more-btn d-block mt-2" onclick="toggleItems('brand')">Hiện thêm</small>
                        @endif
                    </div>

                    <!-- Lọc theo khoảng giá -->
                    <div class="mb-3">
                        <h6>Khoảng Giá</h6>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <input type="checkbox" 
                                       name="price_range[]" 
                                       value="under_500k" 
                                       {{ in_array('under_500k', request()->input('price_range', [])) ? 'checked' : '' }}>
                                Dưới 500k
                            </li>
                            <li class="list-group-item">
                                <input type="checkbox" 
                                       name="price_range[]" 
                                       value="500k_1m" 
                                       {{ in_array('500k_1m', request()->input('price_range', [])) ? 'checked' : '' }}>
                                500k - 1tr
                            </li>
                            <li class="list-group-item">
                                <input type="checkbox" 
                                       name="price_range[]" 
                                       value="1m_2m" 
                                       {{ in_array('1m_2m', request()->input('price_range', [])) ? 'checked' : '' }}>
                                1tr - 2tr
                            </li>
                            <li class="list-group-item">
                                <input type="checkbox" 
                                       name="price_range[]" 
                                       value="2m_5m" 
                                       {{ in_array('2m_5m', request()->input('price_range', [])) ? 'checked' : '' }}>
                                2tr - 5tr
                            </li>
                            <li class="list-group-item">
                                <input type="checkbox" 
                                       name="price_range[]" 
                                       value="above_5m" 
                                       {{ in_array('above_5m', request()->input('price_range', [])) ? 'checked' : '' }}>
                                Trên 5tr
                            </li>
                        </ul>
                    </div>

                    <button type="submit" class="btn btn-orange w-100">Áp Dụng</button>
                </form>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between mb-3 align-items-center">
                {{-- <h5>Kết quả tìm kiếm cho từ khóa '{{ request()->input('search', '') }}'</h5> --}}
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm" 
                            onclick="sortProducts('price_asc')">Giá: Thấp đến Cao</button>
                    <button class="btn btn-outline-primary btn-sm" 
                            onclick="sortProducts('price_desc')">Giá: Cao đến Thấp</button>
                    <button class="btn btn-outline-primary btn-sm" 
                            onclick="sortProducts('newest')">Mới Nhất</button>
                    <button class="btn btn-outline-primary btn-sm" 
                            onclick="sortProducts('oldest')">Cũ Nhất</button>
                </div>
            </div>
            <div class="row">
                <!-- Sản phẩm -->
                @foreach ($products as $product)
                <div class="col-md-3 mb-4">
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
            <div class="d-flex justify-content-center">
                {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

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
</script>
@endsection