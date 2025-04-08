@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>{{ $title ?? 'Chỉnh Sửa Sản Phẩm' }}</h2>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Tên Sản Phẩm</label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
                   value="{{ old('name', $product->name) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="categories_id">Danh Mục</label>
            <select id="categories_id" name="categories_id" class="form-control @error('categories_id') is-invalid @enderror" required>
                <option value="">Chọn Danh Mục</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('categories_id', $product->categories_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('categories_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="brand_id">Thương Hiệu</label>
            <select id="brand_id" name="brand_id" class="form-control @error('brand_id') is-invalid @enderror">
                <option value="">Chọn Thương Hiệu</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                @endforeach
            </select>
            @error('brand_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Mô Tả</label>
            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" 
                      rows="4">{{ old('description', $product->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="original_price">Giá Gốc</label>
            <input type="number" id="original_price" name="original_price" class="form-control @error('original_price') is-invalid @enderror" 
                   value="{{ old('original_price', $product->original_price) }}" step="0.01" required>
            @error('original_price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="price">Giá Sản Phẩm</label>
            <input type="number" id="price" name="price" class="form-control @error('price') is-invalid @enderror" 
                   value="{{ old('price', $product->price) }}" step="0.01" required>
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="stock">Số Lượng Sản Phẩm</label>
            <input type="number" id="stock" name="stock" class="form-control @error('stock') is-invalid @enderror" 
                   value="{{ old('stock', $product->stock) }}" required>
            @error('stock')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="sku">SKU</label>
            <input type="text" id="sku" name="sku" class="form-control @error('sku') is-invalid @enderror" 
                   value="{{ old('sku', $product->sku) }}" readonly>
            @error('sku')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="image">Ảnh Chính</label>
            @if($product->image)
                <div class="mt-2">
                    <img id="imagePreview" src="{{ Storage::url($product->image) }}" alt="Image Preview" style="max-width: 150px; height: auto;">
                </div>
            @else
                <div class="mt-2">
                    <img id="imagePreview" src="#" alt="Image Preview" style="display: none; max-width: 150px; height: auto;">
                </div>
            @endif
            <input type="file" id="image" name="image" class="form-control @error('image') is-invalid @enderror" 
                   accept="image/*" onchange="previewImage(event)">
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="images">Ảnh Phụ (Chọn nhiều ảnh)</label>
            @if($product->images)
                <div id="imagePreviewContainer" class="mt-2 d-flex flex-wrap">
                    @foreach(json_decode($product->images) as $image)
                        <img src="{{ Storage::url($image) }}" alt="Additional Image" style="max-width: 100px; height: auto; margin: 5px;">
                    @endforeach
                </div>
            @else
                <div id="imagePreviewContainer" class="mt-2 d-flex flex-wrap"></div>
            @endif
            <input type="file" id="images" name="images[]" class="form-control @error('images.*') is-invalid @enderror" 
                   accept="image/*" multiple onchange="previewMultipleImages(event)">
            @error('images.*')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Phần chỉnh sửa biến thể -->
        <div class="form-group">
            <label>Biến Thể Sản Phẩm</label>
            <div id="variations-container">
                @foreach($variations as $index => $variation)
                    <div class="variation-row mb-3 p-3 border rounded">
                        <div class="row align-items-end">
                            <div class="col-md-2">
                                <label>Kích Thước</label>
                                <select name="variations[{{ $index }}][size_id]" class="form-control @error("variations.{$index}.size_id") is-invalid @enderror">
                                    <option value="">Chọn kích thước</option>
                                    @foreach($sizes as $size)
                                        <option value="{{ $size->id }}" {{ old("variations.{$index}.size_id", $variation->size_id) == $size->id ? 'selected' : '' }}>
                                            {{ $size->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error("variations.{$index}.size_id")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label>Màu Sắc</label>
                                <select name="variations[{{ $index }}][color_id]" class="form-control @error("variations.{$index}.color_id") is-invalid @enderror">
                                    <option value="">Chọn màu sắc</option>
                                    @foreach($colors as $color)
                                        <option value="{{ $color->id }}" {{ old("variations.{$index}.color_id", $variation->color_id) == $color->id ? 'selected' : '' }}>
                                            {{ $color->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error("variations.{$index}.color_id")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label>Giá</label>
                                <input type="number" name="variations[{{ $index }}][price]" class="form-control @error("variations.{$index}.price") is-invalid @enderror" 
                                       value="{{ old("variations.{$index}.price", $variation->price) }}" step="0.01" placeholder="Giá bán">
                                @error("variations.{$index}.price")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label>Giá Gốc</label>
                                <input type="number" name="variations[{{ $index }}][original_price]" class="form-control @error("variations.{$index}.original_price") is-invalid @enderror" 
                                       value="{{ old("variations.{$index}.original_price", $variation->original_price) }}" step="0.01" placeholder="Giá gốc">
                                @error("variations.{$index}.original_price")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label>Số Lượng</label>
                                <input type="number" name="variations[{{ $index }}][stock]" class="form-control @error("variations.{$index}.stock") is-invalid @enderror" 
                                       value="{{ old("variations.{$index}.stock", $variation->stock) }}" placeholder="Số lượng">
                                @error("variations.{$index}.stock")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <label>Ảnh</label>
                                @if($variation->image)
                                    <div class="mt-2">
                                        <img class="variation-preview" src="{{ Storage::url($variation->image) }}" alt="Preview" style="max-width: 100px; height: auto; margin-top: 5px;">
                                    </div>
                                @else
                                    <img class="variation-preview" src="#" alt="Preview" style="display: none; max-width: 100px; height: auto; margin-top: 5px;">
                                @endif
                                <input type="file" name="variations[{{ $index }}][image]" class="form-control @error("variations.{$index}.image") is-invalid @enderror" 
                                       accept="image/*" onchange="previewVariationImage(event, {{ $index }})">
                                @error("variations.{$index}.image")
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger remove-variation">Xóa</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-success mt-2" id="add-variation">Thêm Biến Thể</button>
        </div>

        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                   value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Kích Hoạt</label>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Cập Nhật Sản Phẩm</button>
            <a href="{{ route('admin.product.index') }}" class="btn btn-secondary">Hủy</a>
        </div>
    </form>
</div>

<script>
let variationCount = {{ count($variations) }}; // Số lượng biến thể hiện tại

function previewImage(event) {
    const imagePreview = document.getElementById('imagePreview');
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function() {
            imagePreview.style.display = 'block';
            imagePreview.src = reader.result;
        };
        reader.readAsDataURL(file);
    } else {
        imagePreview.style.display = 'none';
    }
}

function previewMultipleImages(event) {
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    imagePreviewContainer.innerHTML = '';
    const files = event.target.files;
    for (let i = 0; i < files.length; i++) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imgElement = document.createElement('img');
            imgElement.src = e.target.result;
            imgElement.style.maxWidth = '100px';
            imgElement.style.height = 'auto';
            imgElement.style.margin = '5px';
            imagePreviewContainer.appendChild(imgElement);
        };
        reader.readAsDataURL(files[i]);
    }
}

function previewVariationImage(event, index) {
    const file = event.target.files[0];
    const preview = event.target.previousElementSibling; // Sửa để lấy ảnh preview chính xác
    if (file) {
        const reader = new FileReader();
        reader.onload = function() {
            preview.style.display = 'block';
            preview.src = reader.result;
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const addVariationBtn = document.getElementById('add-variation');
    const variationsContainer = document.getElementById('variations-container');

    addVariationBtn.addEventListener('click', function() {
        const newVariation = `
            <div class="variation-row mb-3 p-3 border rounded">
                <div class="row align-items-end">
                    <div class="col-md-2">
                        <label>Kích Thước</label>
                        <select name="variations[${variationCount}][size_id]" class="form-control">
                            <option value="">Chọn kích thước</option>
                            @foreach($sizes as $size)
                                <option value="{{ $size->id }}">{{ $size->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Màu Sắc</label>
                        <select name="variations[${variationCount}][color_id]" class="form-control">
                            <option value="">Chọn màu sắc</option>
                            @foreach($colors as $color)
                                <option value="{{ $color->id }}">{{ $color->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Giá</label>
                        <input type="number" name="variations[${variationCount}][price]" class="form-control" step="0.01" placeholder="Giá riêng">
                    </div>
                    <div class="col-md-2">
                        <label>Giá Gốc</label>
                        <input type="number" name="variations[${variationCount}][original_price]" class="form-control" step="0.01" placeholder="Giá gốc">
                    </div>
                    <div class="col-md-2">
                        <label>Số Lượng</label>
                        <input type="number" name="variations[${variationCount}][stock]" class="form-control" placeholder="Số lượng">
                    </div>
                    <div class="col-md-2">
                        <label>Ảnh</label>
                        <input type="file" name="variations[${variationCount}][image]" class="form-control" accept="image/*" onchange="previewVariationImage(event, ${variationCount})">
                        <img class="variation-preview" src="#" alt="Preview" style="display: none; max-width: 100px; height: auto; margin-top: 5px;">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-variation">Xóa</button>
                    </div>
                </div>
            </div>
        `;
        variationsContainer.insertAdjacentHTML('beforeend', newVariation);
        variationCount++;
    });

    variationsContainer.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-variation')) {
            const variationRows = variationsContainer.getElementsByClassName('variation-row');
            if (variationRows.length > 1) {
                event.target.closest('.variation-row').remove();
            }
        }
    });
});
</script>
@endsection