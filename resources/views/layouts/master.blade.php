<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trang Chủ') | HoaiLy Shop</title>
    <link rel="icon" type="image/png"
        href="https://finatech.s3.ap-southeast-1.amazonaws.com/20220929/23109627/Shopee.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
    <link rel="stylesheet" href="{{ asset('css/master.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('styles')
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            prefix: 'tw-',
            corePlugins: {
                preflight: false,
            }
        }
    </script>
</head>

<body>
    <!-- Main Navbar with Mobile Menu -->
    <nav class="navbar shopee-navbar navbar-expand-lg">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand shopee-logo" href="{{ url('/') }}">
                @php
                    $logo = \App\Models\Logo::where('is_active', true)->first();
                @endphp
                @if ($logo)
                    <img src="{{ asset('storage/' . $logo->image) }}" alt="HoaiLy Shop Logo" height="40"
                        onerror="this.src='{{ asset('images/default-logo.png') }}';">
                @else
                    <span class="text-white">HoaiLy Shop</span>
                @endif
            </a>

            <!-- Hamburger Toggler for Mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Collapsible Menu Content -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <form class="d-flex mx-auto shopee-search my-2 my-lg-0" method="GET" action="{{ route('product.index') }}">
                    <div class="input-group" style="width:400px">
                        <input type="text" class="form-control" placeholder="Tìm kiếm trên shophoaily..." name="search"
                            value="{{ request()->input('search') }}">
                        <button class="btn btn-orange" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>

                <!-- Navigation Links -->
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                     <li class="nav-item">
                        <a class="nav-link" href="{{ route('product.index') }}">Trang Chủ</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('product.product') }}">Sản Phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Giới Thiệu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Liên Hệ</a>
                    </li>

                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Đăng Ký</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Đăng Nhập</a>
                        </li>
                    @endguest
                    
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                @if (Auth::user()->avatar)
                                    <img src="{{ asset(Auth::user()->avatar) }}" alt="Avatar" class="rounded-circle me-2"
                                        width="32" height="32">
                                @else
                                    <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim(Auth::user()->email))) }}?s=32&d=mp"
                                        alt="Avatar" class="rounded-circle me-2" width="32" height="32">
                                @endif
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ url('/profile') }}">Hồ sơ</a></li>
                                <li><a class="dropdown-item" href="{{ url('/profile/orders') }}">Đơn hàng của tôi</a></li>
                                <li><a class="dropdown-item" href="{{ route('favorites') }}">Sản phẩm đã thích</a></li>
                                @if (Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item" href="{{ url('/admin') }}">Quản trị</a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">Đăng xuất</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                    <li class="nav-item">
                        <a href="{{ url('/cart') }}" class="nav-link shopee-cart-icon position-relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor"
                                class="bi bi-cart3" viewBox="0 0 16 16">
                                <path
                                    d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l.84 4.479 9.144-.459L13.89 4zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                            </svg>
                            @if ($cartItemCount > 0)
                                <span class="badge bg-danger rounded-circle position-absolute"
                                    style="top: -10px; right: -10px; font-size: 12px;">
                                    {{ $cartItemCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                   
                </ul>
            </div>
        </div>
    </nav>

    <!-- Chatbot Button -->
    <div class="chatbot-button">
        <a href="#chat" id="openChatBot" class="text-decoration-none">
            <i class="fas fa-comment-dots"></i>
        </a>
    </div>

    <!-- Chat Window -->
    <div id="chatWindow" class="chat-window" style="display: none;">
        <div class="chat-header">
            <h5>Chat với HoaiLy</h5>
            <button id="closeChat" class="btn-close"></button>
        </div>
        <div class="chat-body" id="chatMessages">
            <p>Xin chào! Chúng tôi có thể giúp gì cho bạn hôm nay?</p>
        </div>
        <div class="chat-footer">
            <input type="text" id="chatInput" placeholder="Nhập tin nhắn..." class="form-control">
            <button id="sendMessage" class="btn btn-orange">Gửi</button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="shopee-footer bg-light">
        <br>
        <div class="container">
            <div class="row">
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
                <div class="col-md-3">
                    <h5>VỀ HOAILY VIỆT NAM</h5>
                    <ul>
                        <li><a href="#">Tuyển Dụng</a></li>
                        <li><a href="#">Điều Khoản</a></li>
                        <li><a href="#">Chính Sách Bảo Mật</a></li>
                        <li><a href="#">Kênh Người Bán</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h5>THANH TOÁN</h5>
                    <div class="payment-methods"></div>
                </div>
                <div class="col-md-2">
                    <h5>THEO DÕI HOAILY</h5>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                    <h5 class="mt-4">ĐƠN VỊ VẬN CHUYỂN</h5>
                    <div class="tracking-methods">
                        <img src="https://down-vn.img.susercontent.com/file/957f4e2d8f5a5f5a5e2a5f5a5e2a5f5a"
                            alt="SPX Express">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789"
                            alt="Viettel Post">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789"
                            alt="Vietnam Post">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789"
                            alt="J&T Express">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789"
                            alt="GrabExpress">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789" alt="Ninja Van">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789" alt="Be">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789" alt="AhaMove">
                    </div>
                </div>
                <div class="col-md-2">
                    <h5>TẢI ỨNG DỤNG HOAILY</h5>
                    <div class="qr-codes">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789" alt="QR Code">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789" alt="QR Code">
                    </div>
                    <div class="app-downloads">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789" alt="App Store">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789"
                            alt="Google Play">
                        <img src="https://down-vn.img.susercontent.com/file/5e7282bd0f7ee0872f90c789"
                            alt="App Gallery">
                    </div>
                </div>
            </div>
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
    @yield('scripts')
    <script>
        // Chatbot Functionality
        const chatMessages = document.getElementById('chatMessages');
        const chatInput = document.getElementById('chatInput');
        const sendMessage = document.getElementById('sendMessage');
        const openChatBot = document.getElementById('openChatBot');
        const closeChat = document.getElementById('closeChat');
        const chatWindow = document.getElementById('chatWindow');

        openChatBot.addEventListener('click', (e) => {
            e.preventDefault();
            chatWindow.style.display = 'block';
        });

        closeChat.addEventListener('click', () => {
            chatWindow.style.display = 'none';
        });

        function appendMessage(sender, content) {
            const messageElement = document.createElement('div');
            messageElement.classList.add('message', sender);
            messageElement.innerHTML = content;
            chatMessages.appendChild(messageElement);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        sendMessage.addEventListener('click', async () => {
            const userQuery = chatInput.value.trim();
            if (!userQuery) return;

            appendMessage('user', userQuery);
            chatInput.value = '';

            try {
                const response = await fetch('{{ route('search.products') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        query: userQuery
                    })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Response is not JSON');
                }

                const data = await response.json();

                if (data.message) {
                    appendMessage('bot', data.message);
                }

                if (data.products && data.products.length > 0) {
                    let productList = '<div class="product-results">';
                    data.products.forEach(product => {
                        const imagePath = product.image.startsWith('http') ? product.image :
                            `/storage/${product.image}`;
                        productList += `
                            <div class="product-item">
                                <a href="/product/${product.slug}" class="text-decoration-none">
                                    <img src="${imagePath}" alt="${product.name}" class="product-image">
                                    <div class="product-info">
                                        <span>${product.name}</span>
                                        <span class="text-danger">${product.price}đ</span>
                                    </div>
                                </a>
                            </div>`;
                    });
                    productList += '</div>';
                    appendMessage('bot', productList);
                } else if (!data.message) {
                    appendMessage('bot', `Không tìm thấy sản phẩm cho "${userQuery}". Hãy thử từ khóa khác!`);
                }
            } catch (error) {
                console.error('Lỗi:', error);
                appendMessage('bot', 'Có lỗi xảy ra. Vui lòng thử lại!');
            }
        });

        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage.click();
            }
        });
    </script>
</body>

</html>