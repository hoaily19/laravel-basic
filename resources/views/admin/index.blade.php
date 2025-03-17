@extends('layouts.admin')

@section('title', 'Trang Quản Trị')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Chào mừng đến với Trang Quản Trị</h1>
    <p>Chọn các chức năng quản lý dưới đây:</p>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Quản lý Danh mục</h5>
                    <p class="card-text">Thêm, sửa, xóa danh mục sản phẩm.</p>
                    <a href="{{ route('admin.category.index') }}" class="btn btn-primary">Đi tới</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Quản lý Sản phẩm</h5>
                    <p class="card-text">Quản lý thông tin sản phẩm trên website.</p>
                    <a href="#" class="btn btn-primary">Đi tới</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Quản lý Đơn hàng</h5>
                    <p class="card-text">Xem và quản lý các đơn hàng mới.</p>
                    <a href="#" class="btn btn-primary">Đi tới</a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection