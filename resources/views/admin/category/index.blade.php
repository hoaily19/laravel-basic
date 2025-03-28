@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Danh sách danh mục </h2>
    <a href="{{ route('admin.category.create') }}" class="btn btn-primary">Thêm danh mục</a>

    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered mt-3">
        <thead class="text-center">
            <tr>
                <th>#</th>
                <th>Tên Danh Mục</th>
                <th>Slug</th>
                <th>Hình Ảnh</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach ($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->slug }}</td>
                <td>
                    @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" width="80">
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.category.edit', $category->id) }}" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen-to-square"></i></a> |
                    <a href="{{ route('admin.category.delete', $category->id) }}" class="btn btn-sm btn-danger"
                        onclick="return confirm('Bạn có chắc muốn xóa danh mục?')"> <i class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
