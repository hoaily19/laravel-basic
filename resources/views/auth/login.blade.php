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
    .social-btn {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .google-btn, .facebook-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        text-decoration: none;
        color: #333;
        font-size: 14px;
    }
    .google-btn i, .facebook-btn i {
        margin-right: 10px;
    }
    .google-btn {
        background-color: #fff;
    }
</style>

<div class="login-container shadow">
    @if (session('error'))
<script>
    iziToast.error({
        title: 'Lỗi',
        message: '{{ session('error') }}',
        position: 'topRight'
    });
</script>
@endif
    <h2>Đăng Nhập</h2>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div>
            <input type="email" class="form-control" name="email" placeholder="Nhập email" >
            @error('email')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <input type="password" class="form-control" name="password" placeholder="Nhập mật khẩu" >
            @error('password')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Đăng Nhập</button>
        <div class="mb-3">
            <a href="/quen-mat-khau" class="text-decoration-none text-orange"> Quên mật khẩu</a>
        </div>
    </form>
    <div class="text-center mt-3">
        <p>Hoặc đăng ký với</p>
        <div class="social-btn">
            <a href="auth/google" class="google-btn text-decoration-none">
                <i class="fab fa-google"></i> Google
            </a>
        </div>
    </div>
    <div class="text-center mt-3">
        <p>Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a></p>
    </div>
</div>
@endsection