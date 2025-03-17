@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Thêm Danh Mục</h2>

    <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data">
        @include('admin.category.form')
    </form>
</div>
@endsection
