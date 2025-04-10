@extends('layouts.admin')

@section('title', 'Trang Quản Trị')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Chào mừng đến với Trang Quản Trị</h1>
    <!-- Thống kê Người dùng -->
    <div class="col-md-12">
        <div class="card mb-3">
            <div class="card-body p-3"> <!-- Giảm padding -->
                <h5 class="card-title mb-2">Thống kê Người dùng</h5> <!-- Giảm margin -->
                <div class="row g-3"> <!-- Giảm khoảng cách giữa các cột -->
                    <div class="col-md-6">
                        <div class="stat-box p-2 bg-primary rounded"> <!-- Giảm padding -->
                            <h6 class="text-white mb-1 fs-6">Tổng số Người dùng</h6> <!-- Giảm margin và font-size -->
                            <p class="fw-bold fs-4 text-white mb-0">{{ $totalUsers ?? '0' }}</p> <!-- Giảm margin -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-box p-2 bg-success rounded">
                            <h6 class="text-white mb-1 fs-6">So với tháng trước</h6>
                            <p class="fw-bold fs-4 text-white mb-0">
                                {{ $usersComparedToLastMonth >= 0 ? '+' : '' }}{{ $usersComparedToLastMonth ?? '0' }}
                                <small class="text-white">({{ number_format($usersPercentageChange ?? 0, 1) }}%)</small>
                            </p>
                        </div>
                    </div>
                </div>
                <!-- Biểu đồ Người dùng -->
                <div class="mt-3"> <!-- Giảm margin-top -->
                    <canvas id="usersChart" height="300"></canvas> <!-- Giảm height -->
                </div>
            </div>
        </div>
        <!-- Thống kê Đơn hàng -->
        <div class="card mb-3">
            <div class="card-body p-3">
                <h5 class="card-title mb-2">Thống kê Đơn hàng</h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="stat-box p-2 bg-light rounded">
                            <h6 class="mb-1 fs-6">Tổng số đơn hàng</h6>
                            <p class="fw-bold fs-4 text-success mb-0">{{ $totalOrders ?? '0' }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-box p-2 bg-light rounded">
                            <h6 class="mb-1 fs-6">Tổng doanh thu</h6>
                            <p class="fw-bold fs-4 text-success mb-0">{{ number_format($totalRevenue ?? 0, 0, ',', '.') }} VNĐ</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-box p-2 bg-light rounded">
                            <h6 class="mb-1 fs-6">Tổng tồn kho</h6>
                            <p class="fw-bold fs-4 text-danger mb-0">{{ $totalStock ?? '0' }}</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stat-box p-2 bg-light rounded">
                            <h6 class="mb-1 fs-6">Tổng giá trị tồn kho</h6>
                            <p class="fw-bold fs-4 text-danger mb-0">{{ number_format($totalInventoryValue ?? 0, 0, ',', '.') }} VNĐ</p>
                        </div>
                    </div>
                </div>
                <!-- Biểu đồ Đơn hàng -->
                <div class="mt-3">
                    <canvas id="ordersChart" height="500"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stat-box {
        text-align: center;
        border: 1px solid #ddd;
        transition: all 0.3s ease;
    }
    .stat-box:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }
    .stat-box h6 {
        color: #555; /
    }
    .card-title {
        font-size: 1.25rem;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const usersData = {
        totalUsers: {{ $totalUsers ?? 0 }},
        usersThisMonth: {{ $usersThisMonth ?? 0 }},
        usersLastMonth: {{ $usersLastMonth ?? 0 }}
    };

    const ordersData = {
        totalOrders: {{ $totalOrders ?? 0 }},
        totalRevenue: {{ $totalRevenue ?? 0 }},
        totalStock: {{ $totalStock ?? 0 }},
        totalInventoryValue: {{ $totalInventoryValue ?? 0 }}
    };

    // Biểu đồ Người dùng (Bar Chart)
    const usersCtx = document.getElementById('usersChart').getContext('2d');
    new Chart(usersCtx, {
        type: 'bar',
        data: {
            labels: ['Tháng trước', 'Tháng này', 'Tổng cộng'],
            datasets: [{
                label: 'Số người dùng',
                data: [usersData.usersLastMonth, usersData.usersThisMonth, usersData.totalUsers],
                backgroundColor: ['#ffccbc', '#ff5722', '#d81b60'],
                borderColor: ['#ff8a65', '#e64a19', '#ad1457'],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { font: { size: 10 } } 
                },
                x: {
                    ticks: { font: { size: 10 } }
                }
            },
            plugins: {
                legend: { display: true }, 
                tooltip: { bodyFont: { size: 10 } } 
            },
            maintainAspectRatio: false 
        }
    });

    // Biểu đồ Đơn hàng (Mixed Chart: Bar)
    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    new Chart(ordersCtx, {
        type: 'bar',
        data: {
            labels: ['Đơn hàng', 'Doanh thu', 'Tồn kho', 'Giá trị tồn kho'],
            datasets: [
                {
                    label: 'Số lượng',
                    data: [ordersData.totalOrders, null, ordersData.totalStock, null],
                    backgroundColor: '#ff5722',
                    borderColor: '#e64a19',
                    borderWidth: 1
                },
                {
                    type: 'bar',
                    label: 'Số tiền (VNĐ)',
                    data: [null, ordersData.totalRevenue, null, ordersData.totalInventoryValue],
                    backgroundColor: '#d81b60',
                    borderColor: '#ad1457',
                    borderWidth: 1,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { font: { size: 10 } }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    ticks: { font: { size: 10 } },
                    grid: { drawOnChartArea: false }
                },
                x: {
                    ticks: { font: { size: 10 } }
                }
            },
            plugins: {
                legend: { display: true, labels: { font: { size: 10 } } }, // Giảm kích thước chữ legend
                tooltip: { bodyFont: { size: 10 } }
            },
            maintainAspectRatio: false
        }
    });
</script>
@endsection