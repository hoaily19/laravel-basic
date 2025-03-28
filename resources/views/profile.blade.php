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
                        <a class="nav-link active" href="#">Thông tin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.address') }}">Địa Chỉ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.changePassword') }}">Đổi mật khẩu</a>
                    </li>
                </ul>
            </div>

            <!-- Cột bên phải: Thông tin cá nhân -->
            <div class="col-md-9 col-lg-10 profile-content">
                <div class="profile-header">
                    @if ($user->avatar)
                        <img src="{{ asset($user->avatar) }}" alt="Avatar">
                    @else
                        <img src="https://www.gravatar.com/avatar/dfb7d7bb286d54795ab66227e90ff048.jpg?s=80&d=mp&r=g" alt="Avatar">
                    @endif
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Thông tin cá nhân</h5>
                        <form class="profile-form" action="{{ route('profile.update') }}" method="POST"
                            enctype="multipart/form-data">

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email', Auth::user()->email) }}">
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="fullname" name="name"
                                    value="{{ old('name', $user->name) }}">
                            </div>
                            <div class="mb-3">
                                <label for="avatar" class="form-label">Ảnh đại diện:</label>
                                <input type="file" class="form-control" id="avatar" name="avatar">
                                @if ($user->avatar)
                                    <div class="mt-2">
                                        <img src="{{ asset($user->avatar) }}" alt="Avatar"
                                            class="img-thumbnail rounded-circle" width="150" height="150">
                                        <button type="button" class="btn btn-danger btn-sm ml-2"
                                            onclick="deleteAvatar()">Xóa ảnh</button>
                                    </div>
                                @endif
                                @error('avatar')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-save">Lưu thay đổi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function deleteAvatar() {
            if (confirm('Bạn có chắc muốn xóa ảnh đại diện không?')) {
                fetch('{{ route('profile.delete.avatar') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload(); // Tải lại trang để cập nhật giao diện
                    } else {
                        alert('Xóa ảnh thất bại');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Đã xảy ra lỗi');
                });
            }
        }
    </script>
@endsection