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
    @if (session('error'))
        <script>
            iziToast.error({
                title: 'Lỗi',
                message: '{{ session('error') }}',
                position: 'topRight'
            });
        </script>
    @endif

    <style>
        #mainImageContainer {
            position: relative;
            overflow: hidden;
        }

        #mainImage {
            width: 100%;
            transition: opacity 0.3s ease-in-out;
        }

        .additional-image:hover {
            border-color: #ff5722;
            opacity: 0.8;
        }

        .variation-option {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .variation-option:hover {
            background-color: #f9f9f9;
        }

        .btn-outline-danger {
            border-color: #ff5722;
            color: #ff5722;
            font-size: 0.9rem;
        }

        .btn-outline-danger:hover {
            background-color: #ff5722;
            color: #fff;
        }

        .btn-danger {
            background-color: #ff5722;
            border-color: #ff5722;
        }

        .btn-danger:hover {
            background-color: #e64a19;
            border-color: #e64a19;
        }

        .image-format {
            width: 100%;
            max-height: 600px;
            object-fit: contain;
            cursor: pointer;
        }

        .color-option,
        .size-option {
            position: relative;
        }

        .color-label,
        .size-label {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .color-label:hover,
        .size-label:hover {
            border-color: #cbd5e0;
            background-color: #f7fafc;
        }

        .btn-check:checked+.color-label,
        .btn-check:checked+.size-label {
            border-color: #DC1E35;
            background-color: #eff6ff;
        }

        .color-name {
            font-size: 0.9rem;
        }

        .size-label {
            min-width: 60px;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .btn-check:disabled+.color-label,
        .btn-check:disabled+.size-label {
            opacity: 0.5;
            cursor: not-allowed;
        }

        @keyframes select-pop {
            0% {
                transform: scale(0.95);
            }

            50% {
                transform: scale(1.02);
            }

            100% {
                transform: scale(1);
            }
        }

        .btn-check:checked+.color-label,
        .btn-check:checked+.size-label {
            animation: select-pop 0.2s ease-out;
        }

        /* Styles for Reviews */
        .text-danger {
            color: #ff5722 !important;
        }

        .fas.fa-star {
            font-size: 0.9rem;
        }

        .btn-outline-secondary {
            border-color: #6c757d;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            color: #fff;
        }

        /* Styles cho đánh giá sao */
        .star-rating {
            display: inline-flex;
            font-size: 1.5rem;
        }

        .star-rating .star {
            color: #ccc;
            /* Màu mặc định (xám) */
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .star-rating .star.selected {
            color: #ff5722;
            /* Màu khi được chọn (cam) */
        }

        .star-rating .star:hover,
        .star-rating .star:hover~.star {
            color: #ff5722;
            /* Màu khi hover */
        }

        /* Styles for Related Products */
        .shophoaily-product-card {
            border: none;
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .shophoaily-product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .shophoaily-product-image {
            height: 200px;
            object-fit: cover;
        }

        .shophoaily-product-title {
            font-size: 1rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .shophoaily-price-section {
            margin-bottom: 0.5rem;
        }

        .shophoaily-price {
            font-size: 1.1rem;
            font-weight: 600;
            color: #ff5722;
        }

        .shophoaily-rating {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .shophoaily-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .btn-orange {
            background-color: #ff5722;
            border-color: #ff5722;
            color: #fff;
            font-size: 0.85rem;
        }

        .btn-orange:hover {
            background-color: #e64a19;
            border-color: #e64a19;
        }

        .btn-outline-orange {
            border-color: #ff5722;
            color: #ff5722;
            font-size: 0.85rem;
        }

        .btn-outline-orange:hover {
            background-color: #ff5722;
            color: #fff;
        }

        .like-btn {
            transition: color 0.3s ease;
            padding: 4px 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .like-btn.liked {
            color: #ff5722;
            font-weight: bold;
        }

        .like-btn:hover {
            color: #ff5722;
            text-decoration: none;
        }

        .like-text {
            margin-left: 4px;
        }

        .like-count {
            font-weight: normal;
        }
    </style>

    <div class="container my-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('product.index') }}">Trang chủ</a></li>
                @if ($category)
                    <li class="breadcrumb-item"><a href="#">{{ $category->name }}</a></li>
                @else
                    <li class="breadcrumb-item"><a href="#">Không có danh mục</a></li>
                @endif
                @if ($brand)
                    <li class="breadcrumb-item"><a href="#">{{ $brand->name }}</a></li>
                @else
                    <li class="breadcrumb-item"><a href="#">Không có thương hiệu</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>
        <h2 class="mb-3">{{ $product->name }}</h2>

        <div class="row">
            <!-- Ảnh sản phẩm -->
            <div class="col-md-6">
                <div id="mainImageContainer" class="position-relative">
                    @if ($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" id="mainImage"
                            class="img-fluid mb-3 rounded image-format"
                            style="max-height: 400px; object-fit: cover; transition: opacity 0.3s;">
                    @else
                        <img src="https://via.placeholder.com/400" alt="No Image" id="mainImage"
                            class="img-fluid mb-3 rounded image-format">
                    @endif
                </div>

                @if ($product->images)
                    <div class="row g-2">
                        @foreach (json_decode($product->images, true) as $image)
                            <div class="col-3">
                                <img src="{{ Storage::url($image) }}" alt="Additional Image"
                                    class="img-fluid rounded additional-image"
                                    style="max-height: 100px; object-fit: cover; cursor: pointer; border: 2px solid transparent;"
                                    onclick="changeMainImage('{{ Storage::url($image) }}')">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="col-md-6">
                <div class="d-flex align-items-center mb-2">
                    <span class="text-warning me-2">
                        <i class="fas fa-star"></i> {{ number_format($averageRating, 1) }} trên 5
                    </span>
                    <span class="text-muted">| {{ $totalReviews }} Đánh Giá</span>
                    <span class="text-muted ms-2">| 6.9k Đã Bán</span>
                </div>

                <div class="mb-3" id="priceDisplay">
                    <span class="text-danger h4 fw-bold">đ<span
                            id="dynamicPrice">{{ number_format($product->price, 0) }}</span></span>
                </div>
                <p class="mb-2">
                    <strong>Thương hiệu: </strong> <span id="brand">{{ $brand->name }}</span>
                </p>
                <div class="mb-3">
                    <a href="#" class="text-decoration-none text-dark">Ưu đãi</a>
                    <span class="badge bg-warning text-dark ms-2">Mã Giảm 1k</span>
                </div>

                <p class="mb-2"><strong>Số lượng tồn kho:</strong> <span id="stock">{{ $product->stock }}</span></p>
                <p class="mb-2"><strong>SKU:</strong> {{ $product->sku ?? 'N/A' }}</p>
                <p class="mb-2"><strong>Lượt xem:</strong> {{ $product->view_count }}</p>

                <!-- Biến thể sản phẩm -->
                <form id="add-to-cart-form" action="{{ route('cart.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="product_variations_id" id="selected-variation-id">

                    @if (
                        $variations->isNotEmpty() &&
                            $variations->contains(function ($variation) {
                                return !is_null($variation->size_id) || !is_null($variation->color_id);
                            }))
                        <!-- Kích thước -->
                        @if ($variations->contains(fn($variation) => !is_null($variation->size_id)))
                            <div class="form-group mb-4">
                                <label class="d-block mb-2"><strong>Chọn Kích Thước</strong></label>
                                <div id="size-buttons" class="d-flex flex-wrap gap-2" role="group"
                                    aria-label="Size select">
                                    @foreach ($variations->filter(fn($variation) => !is_null($variation->size_id))->unique('size_id') as $variation)
                                        <div class="size-option">
                                            <input type="radio" id="size-{{ $variation->size_id }}" name="size_variation"
                                                class="btn-check" value="{{ $variation->size_id }}"
                                                onchange="updateVariation({{ $variation->id }})">
                                            <label class="size-label" for="size-{{ $variation->size_id }}">
                                                {{ $variation->size_name ?? 'N/A' }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Màu sắc -->
                        @if ($variations->contains(fn($variation) => !is_null($variation->color_id)))
                            <div class="form-group mb-4">
                                <label class="d-block mb-2"><strong>Chọn Màu Sắc</strong></label>
                                <div id="color-buttons" class="d-flex flex-wrap gap-2" role="group"
                                    aria-label="Color select">
                                    @foreach ($variations->filter(fn($variation) => !is_null($variation->color_id))->unique('color_id') as $variation)
                                        <div class="color-option">
                                            <input type="radio" id="color-{{ $variation->color_id }}"
                                                name="color_variation" class="btn-check" value="{{ $variation->color_id }}"
                                                onchange="updateVariation({{ $variation->id }})">
                                            <label class="color-label" for="color-{{ $variation->color_id }}">
                                                <span class="color-name">{{ $variation->color_name ?? 'N/A' }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        <p class="mt-4">Sản phẩm này không có biến thể.</p>
                    @endif

                    <!-- Trường số lượng -->
                    <div class="form-group mb-4">
                        <label for="quantity" class="d-block mb-2"><strong>Số lượng</strong></label>
                        <div class="input-group" style="width: 150px;">
                            <button type="button" class="btn btn-outline-secondary"
                                onclick="updateQuantity(-1)">-</button>
                            <input type="number" name="quantity" id="quantity" class="form-control text-center"
                                value="1" min="1" max="{{ $product->stock }}" required>
                            <button type="button" class="btn btn-outline-secondary"
                                onclick="updateQuantity(1)">+</button>
                        </div>
                        @error('quantity')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-outline-danger btn-lg">Thêm Vào Giỏ Hàng</button>
                        <button type="button" class="btn btn-danger btn-lg">Mua Ngay</button>
                    </div>
                </form>

                <div class="mt-3 small text-muted">
                    <p><i class="fas fa-truck"></i> Vận Chuyển: Nhận trong 22 Th03 - 27 Th03, phí giao hàng
                        <strong>0</strong>
                    </p>
                    <p><i class="fas fa-shield-alt"></i> An Tâm Mua Sắm Cùng Shopee</p>
                    <p><i class="fas fa-undo"></i> Trả hàng miễn phí 15 ngày</p>
                </div>
            </div>
        </div>

        <!-- Mô tả sản phẩm -->
        <div class="row mt-4">
            <div class="col-md-12">
                <h3>Mô tả</h3>
                <p class="mb-2">{{ $product->description ?? 'Không có mô tả' }}</p>
            </div>
        </div>
        <!-- Đánh giá sản phẩm -->
        <div class="row mt-4">
            <div class="col-md-12">
                <h3>Đánh giá sản phẩm</h3>
                <div class="mb-3">
                    <h4 class="text-danger">{{ number_format($averageRating, 1) }} trên 5</h4>
                    <div class="d-flex align-items-center mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $averageRating ? 'text-danger' : 'text-muted' }}"></i>
                        @endfor
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-outline-danger">Tất Cả ({{ $totalReviews }})</button>
                        <button class="btn btn-outline-secondary">5 Sao ({{ $fiveStarReviews }})</button>
                        <button class="btn btn-outline-secondary">4 Sao ({{ $fourStarReviews }})</button>
                        <button class="btn btn-outline-secondary">3 Sao ({{ $threeStarReviews }})</button>
                        <button class="btn btn-outline-secondary">2 Sao ({{ $twoStarReviews }})</button>
                        <button class="btn btn-outline-secondary">1 Sao ({{ $oneStarReviews }})</button>
                        <button class="btn btn-outline-secondary">Có Bình Luận ({{ $withComments }})</button>
                        <button class="btn btn-outline-secondary">Có Hình Ảnh / Video ({{ $withMedia }})</button>
                    </div>
                </div>

                @if (Auth::check())
                    @if ($userReview)
                        <div class="mt-4">
                            <h4>Chỉnh sửa đánh giá của bạn</h4>
                            <form action="{{ route('product.review.update', $userReview->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="rating" class="form-label"><strong>Đánh giá sao</strong></label>
                                    <div class="star-rating">
                                        <input type="hidden" name="rating" id="rating-value"
                                            value="{{ $userReview->rating }}">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span class="star {{ $i <= $userReview->rating ? 'selected' : '' }}"
                                                data-value="{{ $i }}">★</span>
                                        @endfor
                                    </div>
                                    @error('rating')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="comment" class="form-label"><strong>Bình luận</strong></label>
                                    <textarea name="comment" id="comment" class="form-control" rows="3">
                                    {{ preg_replace('/\n\n\[Phản hồi từ cửa hàng - .+?\]: .*/s', '', $userReview->comment) }}
                                </textarea>
                                    @error('comment')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="images" class="form-label"><strong>Thêm ảnh (tùy chọn)</strong></label>
                                    <input type="file" name="images[]" id="images" class="form-control" multiple
                                        accept="image/*">
                                    <input type="hidden" name="existing_images"
                                        value="{{ json_encode($userReview->images) }}">
                                    @if ($userReview->images)
                                        <div class="mt-2">
                                            <p>Ảnh hiện tại:</p>
                                            <div class="d-flex gap-2">
                                                @foreach ($userReview->images as $image)
                                                    <img src="{{ Storage::url($image) }}" alt="Review Image"
                                                        style="max-width: 100px; max-height: 100px; object-fit: cover;">
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    @error('images.*')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-danger">Cập nhật đánh giá</button>
                                    <form action="{{ route('product.review.delete', $userReview->id) }}" method="POST"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa đánh giá này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">Xóa đánh giá</button>
                                    </form>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="mt-4">
                            <h4>Gửi đánh giá của bạn</h4>
                            <form action="{{ route('product.review.store', $product->slug) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="rating" class="form-label"><strong>Đánh giá sao</strong></label>
                                    <div class="star-rating">
                                        <input type="hidden" name="rating" id="rating-value" value="0">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span class="star" data-value="{{ $i }}">★</span>
                                        @endfor
                                    </div>
                                    @error('rating')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="comment" class="form-label"><strong>Bình luận</strong></label>
                                    <textarea name="comment" id="comment" class="form-control" rows="3"></textarea>
                                    @error('comment')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="images" class="form-label"><strong>Thêm ảnh (tùy chọn)</strong></label>
                                    <input type="file" name="images[]" id="images" class="form-control" multiple
                                        accept="image/*">
                                    @error('images.*')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-danger">Gửi đánh giá</button>
                            </form>
                        </div>
                    @endif
                @else
                    <p>Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để gửi đánh giá.</p>
                @endif

                <div class="mt-4">
                    @if ($reviews->isNotEmpty())
                        @foreach ($reviews as $review)
                            <div class="border-bottom py-3">
                                <div class="d-flex align-items-center mb-2">
                                    <img src="{{ $review->user->avatar ?? 'https://via.placeholder.com/40' }}"
                                        alt="User Avatar" class="rounded-circle me-2" style="width: 40px; height: 40px;">
                                    <div>
                                        <strong>{{ $review->user->name }}</strong>
                                        <div class="d-flex align-items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="fas fa-star {{ $i <= $review->rating ? 'text-danger' : 'text-muted' }} me-1"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <p class="text-muted small mb-1">{{ $review->created_at->format('Y-m-d H:i') }}</p>
                                @if ($review->variant)
                                    <p class="text-muted small mb-1">Phân loại hàng: {{ $review->variant }}</p>
                                @endif
                                @php
                                    $userComment = preg_replace(
                                        '/\n\n\[Phản hồi từ cửa hàng - .+?\]: .*/s',
                                        '',
                                        $review->comment,
                                    );
                                    $storeReply = preg_match(
                                        '/\n\n\[Phản hồi từ cửa hàng - (.+?)\]: (.*)/s',
                                        $review->comment,
                                        $matches,
                                    )
                                        ? $matches[2]
                                        : null;
                                    $replyTimestamp = $matches[1] ?? null;
                                @endphp
                                <p class="mb-2">{{ $userComment ?? 'Không có bình luận' }}</p>
                                @if ($storeReply)
                                    <div class="bg-light p-3 rounded mb-2">
                                        <strong>Phản hồi từ cửa hàng</strong> <span
                                            class="text-muted small">({{ $replyTimestamp }})</span>
                                        <p class="mt-1">{{ $storeReply }}</p>
                                    </div>
                                @endif
                                @if ($review->images)
                                    <div class="d-flex gap-2 mb-2">
                                        @foreach ($review->images as $image)
                                            <img src="{{ Storage::url($image) }}" alt="Review Image"
                                                style="max-width: 80px; max-height: 80px; object-fit: cover; border-radius: 4px;">
                                        @endforeach
                                    </div>
                                @endif
                                <div class="mt-2 d-flex align-items-center">
                                    @if (Auth::check())
                                        <form action="{{ route('product.review.like', $review->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @php
                                                $hasLiked = $review->likes()->where('user_id', Auth::id())->exists();
                                            @endphp
                                            <button type="submit"
                                                class="btn btn-link text-muted small like-btn {{ $hasLiked ? 'liked' : '' }}"
                                                title="{{ $hasLiked ? 'Bỏ thích' : 'Thích' }}">
                                                <i class="fas fa-thumbs-up"></i>
                                                <span class="like-text">{{ $hasLiked ? 'Đã thích' : 'Thích' }}</span>
                                                (<span class="like-count">{{ $review->likes_count }}</span>)
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-link text-muted small like-btn">
                                            <i class="fas fa-thumbs-up"></i> Thích
                                            (<span class="like-count">{{ $review->likes_count }}</span>)
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        <div class="mt-3">
                            {{ $reviews->links() }}
                        </div>
                    @else
                        <p>Chưa có đánh giá nào cho sản phẩm này.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <h3>Sản phẩm liên quan</h3>
                @if ($relatedProducts->isNotEmpty())
                    <div class="row">
                        @foreach ($relatedProducts as $relatedProduct)
                            <div class="col-md-3 mb-4">
                                <div class="card shophoaily-product-card">
                                    <img src="{{ Storage::url($relatedProduct->image) }}"
                                        class="card-img-top shophoaily-product-image" alt="{{ $relatedProduct->name }}">
                                    <div class="card-body p-2">
                                        <h6 class="shophoaily-product-title">
                                            @php
                                                $words = explode(' ', $relatedProduct->name);
                                                $limitedName =
                                                    count($words) > 5
                                                        ? implode(' ', array_slice($words, 0, 5)) . '...'
                                                        : $relatedProduct->name;
                                            @endphp
                                            {{ $limitedName }}
                                        </h6>
                                        <div class="shophoaily-price-section">
                                            <span class="shophoaily-price">{{ number_format($relatedProduct->price) }}
                                                VNĐ</span>
                                        </div>
                                        <div class="shophoaily-rating">
                                            <span><i class="fas fa-star text-warning"></i> 4.5</span> |
                                            <span>Đã bán 1k</span>
                                        </div>
                                        <div class="shophoaily-buttons">
                                            <a href="{{ route('product.show', $relatedProduct->slug) }}"
                                                class="btn btn-sm btn-orange w-100 mb-1">Mua Ngay</a>
                                            <form action="{{ route('cart.store') }}" method="POST" class="w-100">
                                                @csrf
                                                <input type="hidden" name="product_id"
                                                    value="{{ $relatedProduct->id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn btn-sm btn-outline-orange w-100">Thêm
                                                    Vào Giỏ</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>Không có sản phẩm liên quan.</p>
                @endif
            </div>
        </div>
    </div>

    <script>
        function changeMainImage(imageUrl) {
            const mainImage = document.getElementById('mainImage');
            if (!mainImage) {
                console.error('Main image element not found');
                return;
            }
            if (!imageUrl || typeof imageUrl !== 'string' || imageUrl.trim() === '') {
                console.error('Invalid image URL:', imageUrl);
                return;
            }
            console.log('Changing main image to:', imageUrl);
            mainImage.style.opacity = '0';
            mainImage.onerror = () => {
                console.error('Failed to load image:', imageUrl);
                mainImage.src = 'https://via.placeholder.com/400';
                mainImage.style.opacity = '1';
            };
            setTimeout(() => {
                mainImage.src = imageUrl;
                mainImage.style.opacity = '1';
            }, 300);
        }

        function updateVariation(variationId) {
            const variations = @json($variations);
            const variation = variations.find(v => v.id == variationId);
            if (variation) {
                const dynamicPrice = document.getElementById('dynamicPrice');
                const stock = document.getElementById('stock');
                const selectedVariationInput = document.getElementById('selected-variation-id');
                const quantityInput = document.getElementById('quantity');

                if (variation.price) {
                    dynamicPrice.textContent = number_format(variation.price, 0);
                } else {
                    dynamicPrice.textContent = number_format({{ $product->price }}, 0);
                }

                if (variation.image) {
                    changeMainImage('{{ Storage::url('') }}' + variation.image);
                } else {
                    @if ($product->image)
                        changeMainImage('{{ Storage::url($product->image) }}');
                    @else
                        changeMainImage('https://via.placeholder.com/400');
                    @endif
                }

                stock.textContent = variation.stock ?? {{ $product->stock }};
                quantityInput.max = variation.stock ?? {{ $product->stock }};
                selectedVariationInput.value = variation.id;
            } else {
                console.error('Variation not found for ID:', variationId);
            }
        }

        function updateQuantity(change) {
            const quantityInput = document.getElementById('quantity');
            let quantity = parseInt(quantityInput.value) + change;
            const maxQuantity = parseInt(quantityInput.max);

            if (quantity < 1) quantity = 1;
            if (quantity > maxQuantity) quantity = maxQuantity;

            quantityInput.value = quantity;
        }

        function number_format(number, decimals = 0, dec_point = '.', thousands_sep = ',') {
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            const n = !isFinite(+number) ? 0 : +number;
            const prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
            const sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep;
            const dec = (typeof dec_point === 'undefined') ? '.' : dec_point;
            let s = '';
            const toFixedFix = function(n, prec) {
                const k = Math.pow(10, prec);
                return Math.round(n * k) / k;
            };
            s = (prec ? toFixedFix(n, prec) : Math.round(n)).toString().split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        // JavaScript cho hệ thống sao đánh giá
        document.addEventListener('DOMContentLoaded', function() {
            const starRatings = document.querySelectorAll('.star-rating');
            starRatings.forEach(rating => {
                const stars = rating.querySelectorAll('.star');
                const ratingValue = rating.querySelector('#rating-value');

                stars.forEach(star => {
                    star.addEventListener('click', function() {
                        const value = this.getAttribute('data-value');
                        ratingValue.value = value;

                        stars.forEach(s => {
                            if (parseInt(s.getAttribute('data-value')) <= value) {
                                s.classList.add('selected');
                            } else {
                                s.classList.remove('selected');
                            }
                        });
                    });

                    star.addEventListener('mouseover', function() {
                        const value = this.getAttribute('data-value');
                        stars.forEach(s => {
                            if (parseInt(s.getAttribute('data-value')) <= value) {
                                s.classList.add('selected');
                            } else {
                                s.classList.remove('selected');
                            }
                        });
                    });

                    rating.addEventListener('mouseout', function() {
                        const selectedValue = ratingValue.value;
                        stars.forEach(s => {
                            if (selectedValue && parseInt(s.getAttribute(
                                    'data-value')) <= selectedValue) {
                                s.classList.add('selected');
                            } else {
                                s.classList.remove('selected');
                            }
                        });
                    });
                });
            });
        });
    </script>
@endsection
