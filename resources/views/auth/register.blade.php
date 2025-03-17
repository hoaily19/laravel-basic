@extends('layouts.master')
@section('title', 'Đăng Ký')
@section('content')
<div class="register-container">
    <h2>Đăng Ký</h2>
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="mb-3">
            <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên" required>
            @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Nhập số điện thoại" required>
            @error('phone')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email" required>
            @error('email')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu"
                required>
            @error('password')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                placeholder="Xác nhận mật khẩu" required>
            @error('password_confirmation')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group row mb-0">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    Register
                </button>
            </div>
        </div>    
    </form>
    <div class="text-center mt-3">
        <p>Hoặc đăng ký với</p>
        <div class="social-btn">
            <a href="#" class="google-btn" style="margin-bottom: 10px">
                <i class="fab fa-google"></i> Đăng ký với Google
            </a>
            <a href="#" class="facebook-btn">
                <i class="fab fa-facebook-f"></i> Đăng ký với Facebook
            </a>
        </div>
    </div>
    <div class="login-ok">
        <a href="/login" class="">Đăng Nhập</a>
    </div>
</div>
@endsection

