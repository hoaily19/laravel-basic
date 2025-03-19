@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Danh Thương Hiệu Sản Phẩm </h2>
    <a href="{{ route('admin.brand.create') }}" class="btn btn-primary">Thêm thương hiệu</a>

    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Thương Hiệu</th>
                <th>Danh Mục</th>
                <th>Hình Ảnh</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($brands as $brand)
            <tr>
                <td>{{ $brand->id }}</td>
                <td>{{ $brand->name }}</td>
                <td>{{ $brand->categories ? $brand->categories->name : 'Không có danh mục' }}</td>
                <td>
                    @if($brand->image)
                    <img src="{{ asset('storage/' . $brand->image) }}" width="80">
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.brand.edit', $brand->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <a href="{{ route('admin.brand.delete', $brand->id) }}" class="btn btn-sm btn-danger"
                        onclick="return confirm('Bạn có chắc muốn xóa danh mục?')">Xóa</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
