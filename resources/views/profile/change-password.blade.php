@extends('layouts.master')

@section('content')
<style>
    .sidebar {
        background-color: #fff;
        padding: 20px 0;
    }

    .sidebar .nav-link {
        color: #333;
        padding: 10px 20px;
        font-weight: 500;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background-color: #f0f2f5;
        color: #ff6200;
    }

    .profile-content {
        padding: 30px;
    }

    .profile-header {
        display: flex;
        align-items: center;
        margin-bottom: 30px;
    }

    .profile-header img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        margin-right: 20px;
    }

    .profile-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: bold;
    }

    .profile-form .form-label {
        font-weight: 500;
    }

    .profile-form .form-control {
        background-color: #f8f9fa;
        border-radius: 5px;
    }

    .btn-save {
        background-color: #ff6200;
        border-color: #ff6200;
        color: #fff;
    }

    .btn-save:hover {
        background-color: #e55a00;
        border-color: #e55a00;
    }
</style>
<div class="container mt-3">
    <div class="row">
        <!-- Cột bên trái: Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.profile') }}">Thông tin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.address') }}">Địa Chỉ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('favorites') }}">Sản phẩm yêu thích</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.orders') }}">Đơn Mua</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('profile.changePassword') }}">Đổi mật khẩu</a>
                </li>
            </ul>
        </div>

        <!-- Cột bên phải: Đổi mật khẩu -->
        <div class="col-md-9 col-lg-10 profile-content">
            <div class="profile-header">
                @if(Auth::user()->avatar)
                    <img src="{{ asset(Auth::user()->avatar) }}" alt="Avatar">
                @else
                    <img src="https://fullstack.edu.vn/assets/f8-icon-lV2rGpF0.png" alt="Avatar">
                @endif
                <h2>{{ Auth::user()->username }}</h2>
                <div class="profile-header-info">
                    <h2>{{ $user->name }}</h2>
                    <p>{{ $user->email }}</p>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Đổi mật khẩu</h5>
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <form class="profile-form" action="{{ route('profile.updatePassword') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                            @error('current_password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu mới</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                        
                        <button type="submit" class="btn btn-save">Cập nhật mật khẩu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection