@extends('layouts.master')

@section('content')
    <div class="container my-4">
        <h1 class="mb-3">{{ $product->name }}</h1>

        <!-- Hiển thị thông báo nếu có -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <!-- Ảnh sản phẩm -->
            <div class="col-md-6">
                <!-- Ảnh chính -->
                <div id="mainImageContainer" class="position-relative">
                    @if ($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" id="mainImage" class="img-fluid mb-3 rounded" style="max-height: 400px; object-fit: cover; transition: opacity 0.3s;">
                    @else
                        <img src="https://via.placeholder.com/400" alt="No Image" id="mainImage" class="img-fluid mb-3 rounded">
                    @endif
                </div>

                <!-- Ảnh phụ -->
                @if ($product->images)
                    <div class="row g-2">
                        @foreach (json_decode($product->images, true) as $image)
                            <div class="col-3">
                                <img src="{{ Storage::url($image) }}" alt="Additional Image" class="img-fluid rounded additional-image" style="max-height: 100px; object-fit: cover; cursor: pointer; border: 2px solid transparent;" onclick="changeMainImage('{{ Storage::url($image) }}')">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="col-md-6">
                <!-- Đánh giá và lượt bán -->
                <div class="d-flex align-items-center mb-2">
                    <span class="text-warning me-2"><i class="fas fa-star"></i> 4.8</span>
                    <span class="text-muted">| 952 Đánh Giá</span>
                    <span class="text-muted ms-2">| 6.9k Đã Bán</span>
                </div>

                <!-- Giá sản phẩm (động) -->
                <div class="mb-3" id="priceDisplay">
                    <span class="text-danger h4 fw-bold">đ<span id="dynamicPrice">{{ number_format($product->price * 0.8, 0) }}</span></span>
                    <span class="text-danger h4 fw-bold ms-2" id="dynamicPriceMax"> - đ{{ number_format($product->price, 0) }}</span>
                    <span class="text-decoration-line-through text-muted ms-2" id="originalPrice">đ{{ number_format($product->price * 1.2, 0) }}</span>
                    <span class="badge bg-success ms-2" id="discountBadge">-43%</span>
                </div>

                <!-- Thông tin shop -->
                <div class="mb-3">
                    <a href="#" class="text-decoration-none text-dark">Cửa Shop</a>
                    <span class="badge bg-warning text-dark ms-2">Mã Giảm 1k</span>
                </div>

                <!-- Mô tả và thông tin khác -->
                <p class="mb-2"><strong>Mô tả:</strong> {{ $product->description ?? 'Không có mô tả' }}</p>
                <p class="mb-2"><strong>Số lượng tồn kho:</strong> {{ $product->stock }}</p>
                <p class="mb-2"><strong>SKU:</strong> {{ $product->sku ?? 'N/A' }}</p>
                <p class="mb-2"><strong>Lượt xem:</strong> {{ $product->view_count }}</p>

                <!-- Biến thể sản phẩm -->
                @if ($variations->isNotEmpty())
                    <h5 class="mt-4">Biến Thể Sản Phẩm</h5>
                    <div class="mb-3">
                        @foreach ($variations as $variation)
                            <div class="form-check variation-option">
                                <input type="radio" class="form-check-input" name="variation" id="variation_{{ $variation->id }}" value="{{ $variation->id }}" onchange="updateVariation({{ $variation->id }})">
                                <label class="form-check-label" for="variation_{{ $variation->id }}">
                                    {{ $variation->size ?? 'N/A' }} - {{ $variation->color ?? 'N/A' }} - đ{{ number_format($variation->price ?? $product->price, 0) }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="mt-4">Sản phẩm này không có biến thể.</p>
                @endif

                <!-- Nút mua hàng -->
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-danger btn-lg" type="button">Thêm Vào Giỏ Hàng</button>
                    <button class="btn btn-danger btn-lg" type="button">Mua Ngay</button>
                </div>

                <!-- Chính sách -->
                <div class="mt-3 small text-muted">
                    <p><i class="fas fa-truck"></i> Vận Chuyển: Nhận trong 22 Th03 - 27 Th03, phí giao hàng <strong>0</strong></p>
                    <p><i class="fas fa-shield-alt"></i> An Tâm Mua Sắm Cùng Shopee</p>
                    <p><i class="fas fa-undo"></i> Trả hàng miễn phí 15 ngày</p>
                </div>
            </div>
        </div>

        <!-- Nút quay lại -->
        <div class="mt-4">
            <a href="{{ route('product.index') }}" class="btn btn-secondary">Quay lại danh sách sản phẩm</a>
        </div>
    </div>

    <style>
        /* CSS cho ảnh chính và phụ */
        #mainImageContainer {
            position: relative;
            overflow: hidden;
        }
        #mainImage {
            width: 100%;
            transition: opacity 0.3s ease-in-out;
        }
        .additional-image:hover {
            border-color: #ff5722; /* Màu cam khi hover */
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
    </style>

    <script>
        function changeMainImage(imageUrl) {
            const mainImage = document.getElementById('mainImage');
            mainImage.style.opacity = '0';
            setTimeout(() => {
                mainImage.src = imageUrl;
                mainImage.style.opacity = '1';
            }, 300);
        }

        function updateVariation(variationId) {
            const variation = @json($variations)->find(v => v.id == variationId);
            if (variation) {
                // Cập nhật giá
                const dynamicPrice = document.getElementById('dynamicPrice');
                const dynamicPriceMax = document.getElementById('dynamicPriceMax');
                const originalPrice = document.getElementById('originalPrice');
                const discountBadge = document.getElementById('discountBadge');

                // Nếu biến thể có giá riêng, hiển thị giá của biến thể
                if (variation.price) {
                    const price = variation.price;
                    const discountedPrice = price * 0.8; // Giá giảm 20%
                    const originalPriceValue = price * 1.2; // Giá gốc giả định (cao hơn 20%)
                    const discountPercentage = Math.round(((originalPriceValue - discountedPrice) / originalPriceValue) * 100);

                    dynamicPrice.textContent = number_format(discountedPrice, 0);
                    dynamicPriceMax.textContent = ' - đ' + number_format(price, 0);
                    originalPrice.textContent = 'đ' + number_format(originalPriceValue, 0);
                    discountBadge.textContent = `-${discountPercentage}%`;
                } else {
                    // Nếu biến thể không có giá riêng, quay lại giá của sản phẩm
                    dynamicPrice.textContent = number_format({{ $product->price * 0.8 }}, 0);
                    dynamicPriceMax.textContent = ' - đ' + number_format({{ $product->price }}, 0);
                    originalPrice.textContent = 'đ' + number_format({{ $product->price * 1.2 }}, 0);
                    discountBadge.textContent = '-43%';
                }

                // Cập nhật ảnh
                if (variation.image) {
                    changeMainImage('{{ Storage::url('') }}' + variation.image);
                } else {
                    @if ($product->image)
                        changeMainImage('{{ Storage::url($product->image) }}');
                    @else
                        changeMainImage('https://via.placeholder.com/400');
                    @endif
                }
            } else {
                console.error('Variation not found for ID:', variationId);
            }
        }

        // Hàm định dạng số (tương tự number_format trong PHP)
        function number_format(number, decimals = 0, dec_point = '.', thousands_sep = ',') {
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            const n = !isFinite(+number) ? 0 : +number;
            const prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
            const sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep;
            const dec = (typeof dec_point === 'undefined') ? '.' : dec_point;
            let s = '';
            const toFixedFix = function (n, prec) {
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

        // Tạm thời thêm Font Awesome nếu chưa có
        if (!document.querySelector('link[href*="fontawesome"]')) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css';
            document.head.appendChild(link);
        }

        // Tự động chọn biến thể đầu tiên (nếu có)
        document.addEventListener('DOMContentLoaded', function() {
            const firstVariation = document.querySelector('input[name="variation"]');
            if (firstVariation) {
                firstVariation.checked = true;
                updateVariation(firstVariation.value);
            } else {
                console.log('No variations found.');
            }
        });
    </script>
@endsection