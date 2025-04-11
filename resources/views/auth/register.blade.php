@extends('layouts.master')
@section('title', 'Đăng Ký')
@section('content')
<style>
    /* CSS tùy chỉnh để giống Shopee */
    .register-container {
        max-width: 400px;
        margin: 50px auto;
        padding: 20px;
        font-family: 'Roboto', sans-serif;
    }
    .register-container h2 {
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
    }
    .btn-primary:hover {
        background-color: #f05d40;
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
    .facebook-btn {
        background-color: #fff;
    }
    .login-ok {
        text-align: center;
        margin-top: 20px;
    }
    .login-ok a {
        color: #EE4D2D;
        text-decoration: none;
    }
    .login-ok a:hover {
        text-decoration: underline;
    }
    .alert-danger {
        font-size: 12px;
        padding: 5px;
        margin-top: -10px;
        margin-bottom: 10px;
    }
</style>

<div class="register-container shadow">
    <h2>Đăng Ký</h2>
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div>
            <input type="text" class="form-control" id="name" name="name" placeholder="Nhập tên" >
            @error('name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="Nhập số điện thoại" >
            @error('phone')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email" >
            @error('email')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu" >
            @error('password')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Xác nhận mật khẩu" >
            @error('password_confirmation')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary w-100">Đăng Ký</button>
    </form>

    <div class="text-center mt-3">
        <p>Hoặc đăng ký với</p>
        <div class="social-btn">
            <a href="#" class="google-btn">
                <i class="fab fa-google"></i> Google
            </a>
            <a href="#" class="facebook-btn">
                <i class="fab fa-facebook-f"></i> Facebook
            </a>
        </div>
    </div>

    <div class="login-ok">
        <p>Đã có tài khoản? <a href="/login">Đăng Nhập</a></p>
    </div>
</div>
@endsection