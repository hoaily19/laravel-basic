@extends('layouts.master')

@section('content')
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
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="row">
            <!-- Ảnh sản phẩm -->
            <div class="col-md-6">
                <div id="mainImageContainer" class="position-relative">
                    @if ($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" id="mainImage" class="img-fluid mb-3 rounded image-format" style="max-height: 400px; object-fit: cover; transition: opacity 0.3s;">
                    @else
                        <img src="https://via.placeholder.com/400" alt="No Image" id="mainImage" class="img-fluid mb-3 rounded image-format">
                    @endif
                </div>

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
                <div class="d-flex align-items-center mb-2">
                    <span class="text-warning me-2"><i class="fas fa-star"></i> 4.8</span>
                    <span class="text-muted">| 952 Đánh Giá</span>
                    <span class="text-muted ms-2">| 6.9k Đã Bán</span>
                </div>

                <div class="mb-3" id="priceDisplay">
                    <span class="text-danger h4 fw-bold">đ<span id="dynamicPrice">{{ number_format($product->price, 0) }}</span></span>
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

                    @if ($variations->isNotEmpty() && $variations->contains(function ($variation) {
                        return !is_null($variation->size_id) || !is_null($variation->color_id);
                    }))
                        <!-- Kích thước -->
                        @if ($variations->contains(fn($variation) => !is_null($variation->size_id)))
                            <div class="form-group mb-4">
                                <label class="d-block mb-2"><strong>Chọn Kích Thước</strong></label>
                                <div id="size-buttons" class="d-flex flex-wrap gap-2" role="group" aria-label="Size select">
                                    @foreach ($variations->filter(fn($variation) => !is_null($variation->size_id))->unique('size_id') as $variation)
                                        <div class="size-option">
                                            <input type="radio" id="size-{{ $variation->size_id }}" name="size_variation" class="btn-check" value="{{ $variation->size_id }}" onchange="updateVariation({{ $variation->id }})">
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
                                <div id="color-buttons" class="d-flex flex-wrap gap-2" role="group" aria-label="Color select">
                                    @foreach ($variations->filter(fn($variation) => !is_null($variation->color_id))->unique('color_id') as $variation)
                                        <div class="color-option">
                                            <input type="radio" id="color-{{ $variation->color_id }}" name="color_variation" class="btn-check" value="{{ $variation->color_id }}" onchange="updateVariation({{ $variation->id }})">
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
                            <button type="button" class="btn btn-outline-secondary" onclick="updateQuantity(-1)">-</button>
                            <input type="number" name="quantity" id="quantity" class="form-control text-center" value="1" min="1" max="{{ $product->stock }}" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="updateQuantity(1)">+</button>
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
                    <p><i class="fas fa-truck"></i> Vận Chuyển: Nhận trong 22 Th03 - 27 Th03, phí giao hàng <strong>0</strong></p>
                    <p><i class="fas fa-shield-alt"></i> An Tâm Mua Sắm Cùng Shopee</p>
                    <p><i class="fas fa-undo"></i> Trả hàng miễn phí 15 ngày</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12"> 
                <h3 class="">Mô tả</h3>
                <p class="mb-2">{{ $product->description ?? 'Không có mô tả' }}</p>
            </div>
        </div>
    </div>

    <style>
        #mainImageContainer { position: relative; overflow: hidden; }
        #mainImage { width: 100%; transition: opacity 0.3s ease-in-out; }
        .additional-image:hover { border-color: #ff5722; opacity: 0.8; }
        .variation-option { margin-bottom: 10px; padding: 8px; border: 1px solid #ddd; border-radius: 4px; transition: background-color 0.3s; }
        .variation-option:hover { background-color: #f9f9f9; }
        .btn-outline-danger { border-color: #ff5722; color: #ff5722; }
        .btn-outline-danger:hover { background-color: #ff5722; color: #fff; }
        .btn-danger { background-color: #ff5722; border-color: #ff5722; }
        .btn-danger:hover { background-color: #e64a19; border-color: #e64a19; }
        .image-format { width: 100%; max-height: 600px; object-fit: contain; cursor: pointer; }
        .color-option, .size-option { position: relative; }
        .color-label, .size-label { display: flex; align-items: center; gap: 8px; padding: 8px 16px; border: 2px solid #e2e8f0; border-radius: 8px; cursor: pointer; transition: all 0.2s ease; }
        .color-label:hover, .size-label:hover { border-color: #cbd5e0; background-color: #f7fafc; }
        .btn-check:checked+.color-label, .btn-check:checked+.size-label { border-color: #DC1E35; background-color: #eff6ff; }
        .color-name { font-size: 0.9rem; }
        .size-label { min-width: 60px; justify-content: center; font-size: 0.9rem; font-weight: 500; }
        .btn-check:disabled+.color-label, .btn-check:disabled+.size-label { opacity: 0.5; cursor: not-allowed; }
        @keyframes select-pop { 0% { transform: scale(0.95); } 50% { transform: scale(1.02); } 100% { transform: scale(1); } }
        .btn-check:checked+.color-label, .btn-check:checked+.size-label { animation: select-pop 0.2s ease-out; }
    </style>

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
    </script>
@endsection