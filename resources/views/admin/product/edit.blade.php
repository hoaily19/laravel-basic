@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Chỉnh sửa sản phẩm</h2>
        <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Important for PUT requests to indicate we're updating the resource -->

            <div class="form-group">
                <label for="name">Tên Sản Phẩm</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="category">Danh Mục</label>
                <select id="category" name="categories_id" class="form-control" required>
                    <option value="">Chọn Danh Mục</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('categories_id', $product->categories_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('categories_id')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Mô Tả</label>
                <textarea id="description" name="description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="form-group">
                <label for="price">Giá</label>
                <input type="number" id="price" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
            </div>

            <div class="form-group">
                <label for="stock">Số Lượng</label>
                <input type="number" id="stock" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
            </div>

            <div class="form-group">
                <label for="sku">SKU</label>
                <input type="text" id="sku" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
            </div>

            <div class="form-group">
                <label for="image">Ảnh Chính</label>
                @if($product->image)
                    <div>
                        <img src="{{ Storage::url($product->image) }}" alt="Product Image" style="max-width: 200px; max-height: 200px;">
                    </div>
                @endif
                <input type="file" class="form-control" id="image" name="image" value="{{ old('image', $product->image) }}">
            </div>

            <div class="form-group">
                <label for="images">Ảnh Khác</label>
                @if($product->images)
                    <div>
                        @foreach(json_decode($product->images) as $image)
                            <img src="{{ Storage::url($image) }}" alt="Additional Image" style="max-width: 100px; max-height: 100px; margin-right: 10px;">
                        @endforeach
                    </div>
                @endif
                <input type="file" id="images" name="images[]" class="form-control" multiple>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Kích Hoạt</label>
            </div>

            <div class="form-group">
                <h3>Biến thể Sản Phẩm</h3>
                @foreach($variations as $index => $variation)
                    <div class="variation-group">
                        <h5>Biến thể {{ $index + 1 }}</h5>
                        <div class="col-md-2">
                            <label for="variations[{{ $index }}][size_id]">Kích Thước</label>
                            <select name="variations[{{ $index }}][size_id]" class="form-control">
                                <option value="">Chọn kích thước</option>
                                @foreach($sizes as $size)
                                    <option value="{{ $size->id }} {{old ("variations.$index.size_id", $variation->size_id) == $size->id ? 'selected' : ''}}">{{ $size->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label for="variations[{{ $index }}][color_id]">Màu Sắc</label>
                            <select name="variations[{{ $index }}][color_id]" class="form-control">
                                <option value="">Chọn màu sắc</option>
                                @foreach($colors as $color)
                                    <option value="{{ $color->id }} {{old ("variations.$index.color_id", $variation->color_id) == $color->id ? 'selected' : ''}}">{{ $color->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="variations[{{ $index }}][price]">Giá</label>
                            <input type="number" name="variations[{{ $index }}][price]" class="form-control" value="{{ old("variations.$index.price", $variation->price) }}">
                        </div>

                        <div class="form-group">
                            <label for="variations[{{ $index }}][stock]">Số Lượng</label>
                            <input type="number" name="variations[{{ $index }}][stock]" class="form-control" value="{{ old("variations.$index.stock", $variation->stock) }}">
                        </div>

                        <div class="form-group">
                            <label for="variations[{{ $index }}][image]">Ảnh Biến Thể</label>
                            @if($variation->image)
                                <div>
                                    <img src="{{ Storage::url($variation->image) }}" alt="Variation Image" style="max-width: 100px; max-height: 100px;">
                                </div>
                            @endif
                            <input type="file" name="variations[{{ $index }}][image]" class="form-control">
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Cập Nhật</button>
                <a href="{{ route('admin.product.index') }}" class="btn btn-secondary">Thoát</a>
            </div>
        </form>
    </div>
@endsection
