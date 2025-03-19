<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trang Chủ')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
       /* Shopee Top Bar */
        .shopee-top-bar {
            background-color: #ee4d2d; /* Shopee's orange */
            padding: 8px 0;
            font-size: 0.9rem;
        }

        .shopee-top-links a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .shopee-top-links a:hover {
            color: #ffd700; /* Yellow hover effect */
        }

        /* Shopee Main Navbar */
        .shopee-navbar {
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 10px 0;
        }

        .shopee-logo img {
            height: 40px;
        }

        .shopee-search {
            width: 60%;
        }

        .shopee-search .form-control {
            border-radius: 20px 0 0 20px;
            border: 1px solid #ee4d2d;
            padding: 10px;
            font-size: 0.95rem;
        }

        .shopee-search .btn-orange {
            background-color: #ee4d2d;
            color: #fff;
            border-radius: 0 20px 20px 0;
            border: none;
            padding: 10px 15px;
        }

        .shopee-search .btn-orange:hover {
            background-color: #d73211;
        }

        .shopee-cart-icon {
            color: #ffffff;
            transition: color 0.3s ease;
        }

        .shopee-cart-icon:hover {
            color: #ecc5bd;
        }

        /* Shopee Categories Bar */
        .shopee-categories-bar {
            background-color: #fff;
            border-top: 1px solid #e0e0e0;
            padding: 10px 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .category-link {
            color: #555;
            font-size: 0.9rem;
            text-decoration: none;
            margin: 0 10px;
            transition: color 0.3s ease;
        }

        .category-link:hover {
            color: #ee4d2d;
        }

        /* Remove or adjust existing navbar styles */
        .navbar {
            box-shadow: none; 
            background-color: #ee4d2d; 
        }

        .navbar-brand {
            font-weight: normal;
            color: inherit !important;
            font-size: inherit;
        }

        .navbar-nav .nav-link {
            color: inherit !important;
            font-size: inherit;
            transition: none;
        }

        .navbar-nav .nav-link:hover {
            color: inherit !important;
            text-decoration: none;
        }

        .navbar-nav .dropdown-menu {
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-toggler {
            border: none;
        }

        .navbar-toggler-icon {
            background-color: #007bff;
        }

        .category-item {
            position: relative;
            margin: 0 10px;
        }

        .category-link {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
            position: relative;
        }

        .brand-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            min-width: 150px;
            z-index: 1000;
            padding: 5px 0;
        }

        .brand-link {
            display: block;
            padding: 8px 12px;
            text-decoration: none;
            color: #555;
        }

        .brand-link:hover {
            background: #ff5722;
            color: white;
        }

        /* Hiển thị thương hiệu khi di chuột vào danh mục */
        .category-item:hover .brand-dropdown {
            display: block;
        }

       
    </style>
    @yield('styles')
</head>
<body>
    <!-- Top Bar -->
    <div class="shopee-top-bar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Left Side Links -->
                <div class="shopee-top-links">
                    <a href="#" class="text-white">Kênh Người Bán</a>
                    <span class="text-white mx-2">|</span>
                    <a href="#" class="text-white">Trở thành Người bán Shopee</a>
                    <span class="text-white mx-2">|</span>
                    <a href="#" class="text-white">Tải ứng dụng</a>
                    <span class="text-white mx-2">|</span>
                    <a href="#" class="text-white">Kết nối</a>
                    <a href="#" class="text-white ms-2"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-white ms-2"><i class="fab fa-instagram"></i></a>
                </div>
                <!-- Right Side Links -->
                <div class="shopee-top-links">
                    <a href="#" class="text-white"><i class="fas fa-bell me-1"></i> Thông Báo</a>
                    <span class="text-white mx-2">|</span>
                    <a href="#" class="text-white"><i class="fas fa-question-circle me-1"></i> Hỗ Trợ</a>
                    <span class="text-white mx-2">|</span>
                    <a href="#" class="text-white"><i class="fas fa-globe me-1"></i> Tiếng Việt <i class="fas fa-chevron-down"></i></a>
                    <span class="text-white mx-2">|</span>
                    @guest
                        <a href="{{ route('register') }}" class="text-white">Đăng Ký</a>
                        <span class="text-white mx-2">|</span>
                        <a href="{{ route('login') }}" class="text-white">Đăng Nhập</a>
                    @endguest
                    @auth
                        <a href="#" class="text-white dropdown-toggle" id="userDropdown" role="button" 
                        data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-1"></i> {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item text-black" href="{{ url('/profile') }}">Hồ sơ</a></li>
                            @if(Auth::user()->role === 'admin')
                                <li><a class="dropdown-item text-black" href="{{ url('/admin') }}">Quản trị</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Đăng xuất</button>
                                </form>
                            </li>
                        </ul>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <nav class="navbar shopee-navbar">
        <div class="container">
            <a class="navbar-brand shopee-logo" href="{{ url('/') }}">
                @php
                    $logo = \App\Models\Logo::where('is_active', true)->first();
                @endphp
                @if($logo)
                    <img src="{{ asset('storage/' . $logo->image) }}" alt="Thêm logo ở trang admin" height="40" onerror="this.src='{{ asset('images/default-logo.png') }}';">
                @else
                    <span class="text-white">Thêm logo ở trang admin</span> 
                @endif
            </a>
            

            <form class="d-flex mx-auto shopee-search" method="GET" action="{{ route('product.index') }}">
                <div class="input-group">
                    <input type="text" class="form-control me-2" placeholder="Tìm kiếm trên shophoaily..." name="search" value="{{ request()->input('search') }}">
                    <button class="btn btn-orange" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>

            @if (isset($products) && $products->isEmpty())
                <div class="no-products-message">
                    <p>Không tìm thấy sản phẩm "{{ request()->input('search') }}".</p>
                </div>
            @endif

            <a href="{{ url('/cart') }}" class="shopee-cart-icon">
                <i class="fas fa-shopping-cart fa-2x"></i>
            </a>
        </div>
    </nav>


    <!-- Categories Bar -->
    <div class="shopee-categories-bar">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-center">
                @if (isset($categories) && $categories->isNotEmpty())
                    @foreach ($categories as $category)
                        <div class="category-item">
                            <a href="{{ route('product.index', ['category' => $category->id]) }}" class="category-link">{{ $category->name }}</a>
                            
                            @if ($category->brands->isNotEmpty()) 
                                <div class="brand-dropdown">
                                    @foreach ($category->brands as $brand)
                                        <a href="{{ route('product.index', ['category' => $category->id, 'brand' => $brand->id]) }}" class="brand-link">{{ $brand->name }}</a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    

    <div class="container mt-4">
        @yield('content')
    </div>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="footer-logo">ShopHoaiLy</div>
                    <p>Chúng tôi mang đến những sản phẩm tốt nhất cho bạn.</p>
                </div>
                <div class="col-md-4">
                    <div class="footer-links">
                        <h5>Liên kết</h5>
                        <ul class="list-unstyled">
                            <li><a href="{{ url('/about') }}">Giới thiệu</a></li>
                            <li><a href="#">Chính sách bảo mật</a></li>
                            <li><a href="#">Điều khoản dịch vụ</a></li>
                            <li><a href="#">Liên hệ</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="footer-links">
                        <h5>Theo dõi chúng tôi</h5>
                        <a href="#" class="google-btn"><i class="fab fa-google"></i> Google</a>
                        <a href="#" class="facebook-btn"><i class="fab fa-facebook-f"></i> Facebook</a>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>© Copyright <strong>ShopHoaiLy</strong>. All Rights Reserved</p>
                <div class="credits">
                    Designed by <a href="https://bootstrapmade.com/" target="_blank">BootstrapMade</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
