@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Thêm Thương Hiệu</h2>

    <form action="{{ route('admin.brand.store') }}" method="POST" enctype="multipart/form-data">
        @include('admin.brand.form')
    </form>
</div>
@endsection
