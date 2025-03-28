@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>{{$title}}</h2>
    <a href="{{ route('admin.variants.size.create') }}" class="btn btn-primary">
        + Thêm kích thước
    </a>
    <a href="{{ route('admin.variants.color.create') }}" class="btn btn-primary">
        + Thêm màu sắc
    </a>

    @if(session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered mt-3">
        <thead class="text-center">
            <tr>
                <th>#</th>
                <th>Phân Loại</th>
                <th>Mô tả</th>
                <th>Edit</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach ($sizes as $size)
            <tr>
                <td>
                    <span>{{ $size->id }}</span>
                </td>
                <td>Kích thước</td>
                <td>{{ $size->name }}</td>
                <td>
                    <a href="{{ route('admin.variants.size.edit', $size->id) }}" class="btn btn-sm btn-warning">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <a href="{{ route('admin.variants.size.delete', $size->id) }}" class="btn btn-sm btn-danger"
                        onclick="return confirm('Bạn có chắc muốn xóa kích thước này?')">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
            @endforeach

            <tr></tr>
                <td colspan="4">
                    <hr>
                </td>
            </tr>

            <!-- Hiển thị danh sách màu sắc -->
            @foreach ($colors as $color)
            <tr>
                <td>
                    <!-- Hiển thị ô màu sắc -->
                    <span>{{ $color->id }}</span>
                </td>
                <td>Màu sắc</td>
                <td>{{ $color->name }}</td>
                <td>
                    <a href="{{ route('admin.variants.color.edit', $color->id) }}" class="btn btn-sm btn-warning">
                        <i class="fa-solid fa-pen-to-square"></i></a> |
                    <a href="{{ route('admin.variants.color.delete', $color->id) }}" class="btn btn-sm btn-danger"
                        onclick="return confirm('Bạn có chắc muốn xóa màu sắc này?')">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection