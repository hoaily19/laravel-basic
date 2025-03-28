@extends('layouts.admin')

@section('content')
<h2 class="text">{{$title}}</h2>
<form action="{{ route('admin.variants.color.store') }}" method="POST">
    @csrf
    <div class="form-group mb-3">
        <label for="name">Tên Màu</label>
        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" 
               value="{{ old('name') }}" placeholder="Nhập tên màu" >
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <button type="submit" class="btn btn-primary">Thêm </button>
    <a href="{{ route('admin.variants.color.index') }}" class="btn btn-secondary">Thoát</a>
</form>

@endsection
