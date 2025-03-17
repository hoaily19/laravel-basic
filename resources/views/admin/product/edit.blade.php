<!-- resources/views/products/create.blade.php -->

@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>Thêm Sản Phẩm Mới</h2>
        <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
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
                <input type="file" class="form-control" id="image" name="image" value="{{ old('image', $product->image) }}" required>                          
            </div>
        
            <div class="form-group">
                <label for="images">Ảnh Khác</label>
                <input type="file" id="images" name="images[]" class="form-control" multiple>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Kích Hoạt</label>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">Lưu</button>
                <a href="{{ route('admin.product.index') }}" class="btn btn-secondary">Thoát</a>
            </div>
        </form>
    </div>
@endsection
