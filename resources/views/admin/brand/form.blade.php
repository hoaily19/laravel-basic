@csrf
<div class="mb-3">
    <label>Tên Thương Hiệu</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $brand->name ?? '') }}" required>
</div>
<div class="form-group">
    <label for="categories_id">Danh Mục</label>
    <select id="categories_id" name="categories_id" class="form-control @error('categories_id') is-invalid @enderror">
        <option value="">Chọn Danh Mục</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('categories_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    @error('categories_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
<div class="mb-3">
    <label>Mô Tả Thương Hiệu</label>
    <textarea name="description" class="form-control">{{ old('description', $brand->description ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label>Hình Ảnh</label>
    <input type="file" name="image" class="form-control">
    @if(isset($brand) && $brand->image)
        <img src="{{ asset('storage/'.$brand->image) }}" width="100" class="mt-2">
    @endif
</div>

<button class="btn btn-success">Lưu</button>
<a href="{{ route('admin.brand.index') }}" class="btn btn-secondary">Thoát</a>
