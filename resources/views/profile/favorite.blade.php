@extends('layouts.master')

@section('content')
<style>
    /* General styles */
.text-orange {
    color: #ee4d2d;
}

.sidebar {
    background-color: #fff;
    padding: 20px 0;
}

.sidebar .nav-link {
    color: #333;
    padding: 10px 20px;
    font-weight: 500;
    font-size: 0.95rem;
}

.sidebar .nav-link:hover,
.sidebar .nav-link.active {
    background-color: #f0f2f5;
    color: #ee4d2d;
}

.profile-content {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    padding: 30px;
}

/* Product card styles (reused from products.css) */
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
    margin-bottom: 6px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.shopee-price-section {
    margin-bottom: 5px;
}

.shopee-price {
    color: #ee4d2d;
    font-weight: bold;
    font-size: 1rem;
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

.favorite-btn.favorited {
    color: #ee4d2d;
}

.text-muted {
    font-size: 0.95rem;
}

.pagination {
    font-size: 0.95rem;
}

/* Mobile-specific styles */
@media (max-width: 576px) {
    .container {
        padding-left: 10px;
        padding-right: 10px;
    }

    .mt-4 {
        margin-top: 1.5rem !important;
    }

    .col-12,
    .col-md-3,
    .col-md-9,
    .col-lg-2,
    .col-lg-10 {
        flex: 0 0 100%;
        max-width: 100%;
    }

    .sidebar {
        padding: 10px 0;
        margin-bottom: 20px;
    }

    .sidebar .nav-link {
        font-size: 0.9rem;
        padding: 8px 15px;
    }

    .profile-content {
        padding: 15px;
        box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
    }

    h2 {
        font-size: 1.5rem;
    }

    .shopee-product-grid {
        gap: 10px;
    }

    .shopee-product-card {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .shopee-product-image {
        height: 120px;
    }

    .shopee-product-title {
        font-size: 0.8rem;
    }

    .shopee-price {
        font-size: 0.85rem;
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

    .text-muted {
        font-size: 0.9rem;
    }

    .btn-orange {
        font-size: 0.9rem;
        padding: 8px;
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
            <!-- Sidebar -->
            <div class="col-12 col-md-3 col-lg-2 sidebar">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.profile') }}">Thông tin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.address') }}">Địa Chỉ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('favorites') }}">Sản phẩm yêu thích</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.orders') }}">Đơn Mua</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.changePassword') }}">Đổi mật khẩu</a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-12 col-md-9 col-lg-10">
                <div class="profile-content">
                    <h2 class="mb-4 text-center text-orange">Sản Phẩm Yêu Thích</h2>

                    @if ($products->isEmpty())
                        <div class="text-center">
                            <p class="text-muted">Bạn chưa có sản phẩm nào trong danh sách yêu thích.</p>
                            <a href="{{ route('product.product') }}" class="btn btn-orange">Tiếp tục mua sắm</a>
                        </div>
                    @else
                        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 shopee-product-grid">
                            @foreach ($products as $product)
                                <div class="col">
                                    <div class="card shopee-product-card">
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                            class="card-img-top shopee-product-image" alt="{{ $product->name }}">
                                        <div class="card-body p-2">
                                            <h6 class="shopee-product-title">{{ $product->name }}</h6>
                                            <div class="shopee-price-section">
                                                <span class="shopee-price">{{ number_format($product->price) }} VNĐ</span>
                                            </div>
                                            <div class="shopee-rating">
                                                <span><i class="fas fa-star text-warning"></i> 4.5</span> |
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
                                                        class="btn btn-sm favorite-btn flex-shrink-0 favorited"
                                                        data-product-id="{{ $product->id }}"
                                                        title="Bỏ yêu thích"
                                                        aria-label="Bỏ yêu thích">
                                                        <i class="fas fa-heart"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $products->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
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
                                    const productCard = this.closest('.col');
                                    productCard.remove();
                                    if (!document.querySelector('.col')) {
                                        document.querySelector('.shopee-product-grid').outerHTML = `
                                            <div class="text-center">
                                                <p class="text-muted">Bạn chưa có sản phẩm nào trong danh sách yêu thích.</p>
                                                <a href="{{ route('product.product') }}" class="btn btn-orange">Tiếp tục mua sắm</a>
                                            </div>
                                        `;
                                    }
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