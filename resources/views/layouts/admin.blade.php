<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? '' }} | Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>

    <style>
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #212529;
            padding-top: 20px;
        }

        .sidebar .nav-link {
            color: #adb5bd;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: #343a40;
            border-radius: 5px;
        }

        .sidebar .nav-link i {
            font-size: 18px;
        }

        .sidebar .dropdown-menu {
            background-color: #343a40;
            border: none;
            padding: 0;
        }

        .sidebar .dropdown-menu .dropdown-item {
            color: #adb5bd;
            padding: 8px 20px;
            transition: all 0.3s;
        }

        .sidebar .dropdown-menu .dropdown-item:hover {
            color: #fff;
            background-color: #495057;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 200px;
            }

            .content {
                margin-left: 210px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
            }

            .content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style><style>
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #212529;
            padding-top: 20px;
        }

        .sidebar .nav-link {
            color: #adb5bd;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: #343a40;
            border-radius: 5px;
        }

        .sidebar .nav-link i {
            font-size: 18px;
        }

        .sidebar .dropdown-menu {
            background-color: #343a40;
            border: none;
            padding: 0;
        }

        .sidebar .dropdown-menu .dropdown-item {
            color: #adb5bd;
            padding: 8px 20px;
            transition: all 0.3s;
        }

        .sidebar .dropdown-menu .dropdown-item:hover {
            color: #fff;
            background-color: #495057;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 200px;
            }

            .content {
                margin-left: 210px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
            }

            .content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <a href="/" class="text-decoration-none"><h4 class="text-white text-center mb-4">Admin Panel</h4></a>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="/admin#dashboard">
                    <i class="bi bi-house-door me-2"></i>Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                    href="#categoriesMenu" role="button">
                    <div><i class="bi bi-bookmark"></i> Danh Mục</div>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <div class="collapse" id="categoriesMenu">
                    <ul class="nav flex-column ps-3">
                        <li><a class="nav-link" href="{{ route('admin.category.index') }}">Quản lí danh mục</a></li>
                        <li><a class="nav-link" href="{{ route('admin.brand.index') }}">Quản lí thương hiệu</a></li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                    href="#productsMenu" role="button">
                    <div><i class="bi bi-box me-2"></i>Sản Phẩm</div>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <div class="collapse" id="productsMenu">
                    <ul class="nav flex-column ps-3">
                        <li><a class="nav-link" href="{{ route('admin.product.index') }}                                                                                            ">Danh sách sản phẩm</a></li>
                        <li><a class="nav-link" href="{{ route('admin.product.create') }}">Thêm sản phẩm</a></li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                    href="#logoMenu" role="button">
                    <div><i class="bi bi-box me-2"></i>Logo</div>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <div class="collapse" id="logoMenu">
                    <ul class="nav flex-column ps-3">
                        <li><a class="nav-link" href="{{ route('admin.logo.index') }}">Danh sách Logo</a></li>
                        <li><a class="nav-link" href="{{ route('admin.logo.create') }}">Thêm logo</a></li>
                    </ul>
                </div>
            </li>
            

            <li class="nav-item">
                <a class="nav-link" href="/admin/users#users">
                    <i class="bi bi-person me-2"></i>Users
                </a>
            </li>

            <!-- Menu phân cấp Settings -->
            <li class="nav-item">
                <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse"
                    href="#settingsMenu" role="button">
                    <div><i class="bi bi-gear me-2"></i>Settings</div>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <div class="collapse" id="settingsMenu">
                    <ul class="nav flex-column ps-3">
                        <li><a class="nav-link" href="/admin/settings/general#general">General</a></li>
                        <li><a class="nav-link" href="/admin/settings/security#security">Security</a></li>
                        <li><a class="nav-link" href="/admin/settings/notifications#notifications">Notifications</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="/admin/reports#reports">
                    <i class="bi bi-bar-chart me-2"></i>Reports
                </a>
            </li>
        </ul>
    </div>

    <!-- Nội dung chính -->
    <div class="content container-fluid">
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.sidebar .nav-link');

            function removeActive() {
                navLinks.forEach(link => {
                    link.classList.remove('active');
                });
            }

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    if (this.getAttribute('data-bs-toggle') === 'collapse') {
                        return;
                    }

                    removeActive();
                    this.classList.add('active');

                    const href = this.getAttribute('href');
                    window.location.href = href; 
                });
            });

            function setActiveFromHash() {
                const hash = window.location.hash;
                if (hash) {
                    removeActive();
                    const targetLink = document.querySelector(`.sidebar .nav-link[href*="${hash}"]`);
                    if (targetLink) {
                        targetLink.classList.add('active');
                    }
                } else {
                    removeActive();
                    document.querySelector('.sidebar .nav-link').classList.add('active');
                }
            }

            setActiveFromHash();
            window.addEventListener('hashchange', setActiveFromHash);
        });
    </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>