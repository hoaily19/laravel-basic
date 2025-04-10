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
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .profile-header img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .profile-header-info {
            margin-left: 20px;
        }

        .profile-header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            color: #333;
        }

        .profile-header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 14px;
        }

        .profile-form .form-label {
            font-weight: 500;
            color: #555;
            margin-bottom: 8px;
        }

        .profile-form .form-control {
            border-radius: 6px;
            padding: 10px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s;
        }

        .profile-form .form-control:focus {
            border-color: #ff6200;
            box-shadow: 0 0 0 0.25rem rgba(255, 98, 0, 0.1);
        }

        .avatar-container {
            position: relative;
            display: inline-block;
        }

        .avatar-actions {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.5);
            padding: 5px;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
            text-align: center;
        }

        .btn-avatar {
            color: white;
            background: none;
            border: none;
            padding: 2px 5px;
            font-size: 12px;
            cursor: pointer;
        }

        .btn-avatar:hover {
            color: #ff6200;
        }

        .btn-save {
            background-color: #ff6200;
            border-color: #ff6200;
            color: #fff;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-save:hover {
            background-color: #e55a00;
            border-color: #e55a00;
            transform: translateY(-2px);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 25px;
            text-align: center;
            position: relative;
        }

        .card-title:after {
            content: "";
            display: block;
            width: 50px;
            height: 3px;
            background: #ff6200;
            margin: 10px auto 0;
        }

        .alert {
            border-radius: 6px;
        }
    </style>

    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar (giữ nguyên như cũ) -->
            <div class="col-md-3 col-lg-2 sidebar">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('profile.profile') }}">Thông tin</a>
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
                        <a class="nav-link" href="{{ route('profile.changePassword') }}">Đổi mật khẩu</a>
                    </li>
                </ul>
            </div>

            <!-- Nội dung chính -->
            <div class="col-md-9 col-lg-10">
                <div class="profile-content">
                    <div class="profile-header">
                        <div class="avatar-container">
                            @if ($user->avatar)
                                <img src="{{ asset($user->avatar) }}" alt="Avatar">
                            @else
                                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=100&d=mp"
                                    alt="Avatar">
                            @endif
                            <div class="avatar-actions">
                                <button type="button" class="btn-avatar"
                                    onclick="document.getElementById('avatar').click()">
                                    <i class="fas fa-camera"></i> Thay đổi
                                </button>
                                @if ($user->avatar)
                                    <button type="button" class="btn-avatar" onclick="deleteAvatar()">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="profile-header-info">
                            <h2>{{ $user->name }}</h2>
                            <p>{{ $user->email }}</p>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <input type="file" class="d-none" id="avatar" name="avatar" onchange="this.form.submit()">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>

                        <div class=" mt-4">
                            <button type="submit" class="btn btn-save">
                                <i class="fas fa-save me-2"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
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
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert(data.message || 'Xóa ảnh thất bại');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Đã xảy ra lỗi khi xóa ảnh');
                    });
            }
        }
    </script>
@endsection
