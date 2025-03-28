<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trang Chủ') | HoaiLy Shop</title>
    <link rel="icon" type="image/png" href="https://finatech.s3.ap-southeast-1.amazonaws.com/20220929/23109627/Shopee.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/master.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">

    @yield('styles')
    
</head>
    <style >
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
<body>


    <!-- Top Bar -->
    <div class="shopee-top-bar">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Left Side Links -->
                <div class="shopee-top-links">
                    <a href="#" class="text-white">Kênh Người Bán</a>
                    <span class="text-white mx-2">|</span>
                    <a href="#" class="text-white">Trở thành Người bán </a>
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
                    <button class="btn btn-orange border" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </form>

            {{-- @if (isset($products) && $products->isEmpty())
                <div class="no-products-message">
                    <p>Không tìm thấy sản phẩm "{{ request()->input('search') }}".</p>
                </div>
            @endif --}}

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
                            <a href="{{ route('product.product', ['category' => $category->id]) }}" class="category-link">{{ $category->name }}</a>
                            
                            @if ($category->brands->isNotEmpty()) 
                                <div class="brand-dropdown">
                                    @foreach ($category->brands as $brand)
                                        <a href="{{ route('product.product', ['category' => $category->id, 'brand' => $brand->id]) }}" class="brand-link">{{ $brand->name }}</a>
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

    <footer class="shopee-footer bg-light">
        <br>
        <div class="container">
            <div class="row">
                <!-- Customer Service -->
                <div class="col-md-3">
                    <h5>DỊCH VỤ KHÁCH HÀNG</h5>
                    <ul>
                        <li><a href="#">Trung Tâm Trợ Giúp</a></li>
                        <li><a href="#">Hướng Dẫn Mua Hàng/Đặt Hàng</a></li>
                        <li><a href="#">Hướng Dẫn Bán Hàng</a></li>
                        <li><a href="#">Đơn Hàng</a></li>
                        <li><a href="#">Trả Hàng/Hoàn Tiền</a></li>
                        <li><a href="#">Liên Hệ HOAILY</a></li>
                        <li><a href="#">Chính Sách Bảo Hành</a></li>
                    </ul>
                </div>

                <!-- About HOAILY Vietnam -->
                <div class="col-md-3">
                    <h5>VỀ HOAILY VIỆT NAM</h5>
                    <ul>
                        <li><a href="#">Tuyển Dụng</a></li>
                        <li><a href="#">Điều Khoản </a></li>
                        <li><a href="#">Chính Sách Bảo Mật</a></li>
                        <li><a href="#">Kênh Người Bán</a></li>
                    </ul>
                </div>

                <!-- Payment Methods -->
                <div class="col-md-2">
                    <h5>THANH TOÁN</h5>
                    <div class="payment-methods">
                    
                    </div>
                </div>

                <!-- Tracking and Social Media -->
                <div class="col-md-2">
                    <h5>THEO DÕI HOAILY</h5>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                    <h5 class="mt-4">ĐƠN VỊ VẬN CHUYỂN</h5>
                    <div class="tracking-methods">
                        <img src="https://down-vn.img.susercontent.com/file/957f4e2d8f5a5f5a5e2a5f5a5e2a5f5a" alt="SPX Express">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789" alt="Viettel Post">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789" alt="Vietnam Post">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789" alt="J&T Express">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789" alt="GrabExpress">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789" alt="Ninja Van">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789" alt="Be">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789" alt="AhaMove">
                    </div>
                </div>

                <!-- App Downloads -->
                <div class="col-md-2">
                    <h5>TẢI ỨNG DỤNG HOAILY</h5>
                    <div class="qr-codes">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789" alt="QR Code">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789" alt="QR Code">
                    </div>
                    <div class="app-downloads">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789" alt="App Store">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789" alt="Google Play">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789" alt="App Gallery">
                    </div>
                </div>
            </div>

            <!-- Copyright and International Links -->
            <div class="copyright">
                <p>© 2025 HoaiLy. Tất cả các quyền được bảo lưu.</p>
                <div class="international-links">
                    <span>Quốc gia & Khu vực:</span>
                    <a href="#">Singapore</a> |
                    <a href="#">Indonesia</a> |
                    <a href="#">Thái Lan</a> |
                    <a href="#">Malaysia</a> |
                    <a href="#">Việt Nam</a> |
                    <a href="#">Philippines</a> |
                    <a href="#">Brazil</a> |
                    <a href="#">Mexico</a> |
                    <a href="#">Colombia</a> |
                    <a href="#">Chile</a> |
                    <a href="#">Đài Loan</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
