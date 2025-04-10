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

        .profile-content {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .profile-header img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .profile-header-info {
            margin-left: 20px;
        }

        .profile-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            color: #333;
        }

        .profile-header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 14px;
        }

        .profile-form .form-label {
            font-weight: 500;
            color: #555;
            margin-bottom: 8px;
        }

        .profile-form .form-control {
            border-radius: 6px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }

        .profile-form .form-control:focus {
            border-color: #ff6200;
            box-shadow: 0 0 0 0.25rem rgba(255, 98, 0, 0.1);
        }

        .avatar-container {
            position: relative;
            display: inline-block;
        }

        .avatar-actions {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.5);
            padding: 5px;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
            text-align: center;
        }

        .btn-avatar {
            color: white;
            background: none;
            border: none;
            padding: 2px 5px;
            font-size: 12px;
            cursor: pointer;
        }

        .btn-avatar:hover {
            color: #ff6200;
        }

        .btn-save {
            background-color: #ff6200;
            border-color: #ff6200;
            color: #fff;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-save:hover {
            background-color: #e55a00;
            border-color: #e55a00;
            transform: translateY(-2px);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 25px;
            text-align: center;
            position: relative;
        }

        .card-title:after {
            content: "";
            display: block;
            width: 50px;
            height: 3px;
            background: #ff6200;
            margin: 10px auto 0;
        }

        .alert {
            border-radius: 6px;
        }
    </style>

    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar (giữ nguyên như cũ) -->
            <div class="col-md-3 col-lg-2 sidebar">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('profile.profile') }}">Thông tin</a>
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

            <!-- Nội dung chính -->
            <div class="col-md-9 col-lg-10">
                <div class="profile-content">
                    <div class="container mt-4">
                        <h2 class="mb-4 text-center">Sản Phẩm Yêu Thích</h2>

                        @if ($products->isEmpty())
                            <div class="text-center">
                                <p class="text-muted">Bạn chưa có sản phẩm nào trong danh sách yêu thích.</p>
                                <a href="{{ route('product.product') }}" class="btn btn-orange">Tiếp tục mua sắm</a>
                            </div>
                        @else
                            <div class="row">
                                @foreach ($products as $product)
                                    <div class="col-md-3 mb-4">
                                        <div class="card shophoaily-product-card">
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                class="card-img-top shophoaily-product-image" alt="{{ $product->name }}">
                                            <div class="card-body p-2">
                                                <h6 class="shophoaily-product-title">{{ $product->name }}</h6>
                                                <div class="shophoaily-price-section">
                                                    <span class="shophoaily-price">{{ number_format($product->price) }}
                                                        VNĐ</span>
                                                </div>
                                                <div class="shophoaily-rating">
                                                    <span><i class="fas fa-star text-warning"></i> 4.5</span> |
                                                    <span>Đã bán 1k</span>
                                                </div>
                                                <div
                                                    class="shophoaily-buttons d-flex align-items-center justify-content-between">
                                                    <a href="{{ route('product.show', $product->slug) }}"
                                                        class="btn btn-sm btn-orange flex-grow-1 me-1">Mua Ngay</a>
                                                    <div class="d-flex flex-grow-1">
                                                        <a href="{{ route('product.show', $product->slug) }}"
                                                            class="btn btn-sm btn-outline-orange flex-grow-1 me-1"><svg
                                                                xmlns="http://www.w3.org/2000/svg" width="25"
                                                                height="20" fill="currentColor" class="bi bi-cart3"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l.84 4.479 9.144-.459L13.89 4zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                                                            </svg></a>
                                                        <button class="btn btn-sm favorite-btn flex-shrink-0 favorited"
                                                            data-product-id="{{ $product->id }}" title="Bỏ yêu thích">
                                                            <i class="fas fa-heart"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-center">
                                {{ $products->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>


                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const favoriteButtons = document.querySelectorAll('.favorite-btn');

            favoriteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
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
                                    this.closest('.col-md-3').remove();
                                    if (!document.querySelector('.col-md-3')) {
                                        document.querySelector('.row').outerHTML = `
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
