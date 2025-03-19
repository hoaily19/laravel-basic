@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Chỉnh Sửa Thương Hiệu</h2>

    <form action="{{ route('admin.brand.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.brand.form')
    </form>
</div>
@endsection
