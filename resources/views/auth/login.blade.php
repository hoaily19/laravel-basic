@extends('layouts.master')
@section('title', 'Đăng Nhập')
@section('content')
<style>
    /* CSS tùy chỉnh để giống Shopee */
    .login-container {
        max-width: 400px;
        margin: 50px auto;
        padding: 20px;
        font-family: 'Roboto', sans-serif;
    }
    .login-container h2 {
        text-align: center;
        color: #333;
        font-size: 24px;
        margin-bottom: 20px;
    }
    .form-control {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 10px;
        margin-bottom: 15px;
        width: 100%;
    }
    .btn-primary {
        background-color: #EE4D2D;
        border: none;
        padding: 12px;
        font-size: 16px;
        border-radius: 4px;
        width: 100%;
    }
    .btn-primary:hover {
        background-color: #f05d40;
    }
    .text-center a {
        color: #EE4D2D;
        text-decoration: none;
    }
    .text-center a:hover {
        text-decoration: underline;
    }
    .alert-danger {
        font-size: 12px;
        padding: 5px;
        margin-top: -10px;
        margin-bottom: 10px;
    }
</style>

<div class="login-container shadow">
    <h2>Đăng Nhập</h2>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div>
            <input type="email" class="form-control" name="email" placeholder="Nhập email" required>
            @error('email')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <input type="password" class="form-control" name="password" placeholder="Nhập mật khẩu" required>
            @error('password')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Đăng Nhập</button>
    </form>
    <div class="text-center mt-3">
        <p>Hoặc đăng nhập bằng:</p>
        <div class="d-flex justify-content-center gap-2">
            <a href="/auth/facebook" class="btn btn-facebook social-btn w-100">
                <i class="bi bi-facebook me-2"></i> Facebook
            </a>
            <a href="/auth/google" class="btn btn-google social-btn w-100">
                <i class="bi bi-google me-2"></i> Google
            </a>
        </div>
    </div>
    <div class="text-center mt-3">
        <p>Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a></p>
    </div>
</div>
@endsection