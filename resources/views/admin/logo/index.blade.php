@extends('layouts.admin')

@section('title', 'Danh sách Logo')

@section('content')
<div class="container">
    <h2>Danh sách Logo</h2>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Logo</th>
                <th>Hình Ảnh</th>
                <th>Trạng Thái</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logos as $logo)
            <tr>
                <td>{{ $logo->id }}</td>
                <td>{{ $logo->name }}</td>
                <td>
                    @if($logo->image)
                        <img src="{{ asset('storage/' . $logo->image) }}" width="80">
                    @else
                        <span>Không có hình ảnh</span>
                    @endif
                </td>
                <td>
                    @if($logo->is_active)
                        <span class="badge bg-success">Đang hiển thị</span>
                    @else
                        <span class="badge bg-secondary">Không hiển thị</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.logo.toggle-active', $logo->id) }}" class="btn btn-sm {{ $logo->is_active ? 'btn-warning' : 'btn-primary' }}">
                        {{ $logo->is_active ? 'Ẩn' : 'Hiển thị' }}
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection