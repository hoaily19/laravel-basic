@extends('layouts.admin')

@section('title', 'Thống kê tổng quan')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">Chào mừng đến với Trang Quản Trị</h1>

        <div class="mb-4">
            <h5 class="mb-2">Chọn khoảng thời gian thống kê:</h5>
            <div class="text-muted mb-2">Thống kê theo {{ $period == 'day' ? 'ngày' : ($period == 'week' ? 'tuần' : ($period == 'month' ? 'tháng' : 'năm')) }}</div>
            <form method="GET" action="{{ route('admin.index') }}">
                <div class="input-group w-25">
                    <select name="period" class="form-select" onchange="this.form.submit()">
                        <option value="day" {{ $period == 'day' ? 'selected' : '' }}>Ngày</option>
                        <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Tuần</option>
                        <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Tháng</option>
                        <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Năm</option>
                    </select>
                </div>
            </form>
        </div>

        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body p-3">
                    <h5 class="card-title mb-2">Thống kê Người dùng</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="stat-box p-2 bg-primary rounded">
                                <h6 class="text-white mb-1 fs-6">Tổng số Người dùng</h6>
                                <p class="fw-bold fs-4 text-white mb-0">{{ $totalUsers ?? '0' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-box p-2 bg-success rounded">
                                <h6 class="text-white mb-1 fs-6">So với
                                    {{ $period == 'day' ? 'ngày' : ($period == 'week' ? 'tuần' : ($period == 'month' ? 'tháng' : 'năm')) }}
                                    trước</h6>
                                <p class="fw-bold fs-4 text-white mb-0">
                                    {{ $usersComparedToLastPeriod >= 0 ? '+' : '' }}{{ $usersComparedToLastPeriod ?? '0' }}
                                    <small class="text-white">({{ number_format($usersPercentageChange ?? 0, 1) }}%)</small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <canvas id="usersChart" height="300"></canvas>
                    </div>
                </div>
            </div>

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
                                <p class="fw-bold fs-4 text-success mb-0">
                                    {{ number_format($totalRevenue ?? 0, 0, ',', '.') }} VNĐ</p>
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
                                <p class="fw-bold fs-4 text-danger mb-0">
                                    {{ number_format($totalInventoryValue ?? 0, 0, ',', '.') }} VNĐ</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <canvas id="ordersChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body p-3">
                    <h5 class="card-title mb-2">Thống kê Doanh thu và Lợi nhuận</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="stat-box p-2 bg-light rounded">
                                <h6 class="mb-1 fs-6">Tổng doanh thu</h6>
                                <p class="fw-bold fs-4 text-success mb-0">
                                    {{ number_format($totalRevenue ?? 0, 0, ',', '.') }} VNĐ</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-box p-2 bg-light rounded">
                                <h6 class="mb-1 fs-6">Tổng lợi nhuận</h6>
                                <p class="fw-bold fs-4 text-success mb-0">
                                    {{ number_format($totalProfit ?? 0, 0, ',', '.') }} VNĐ</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <canvas id="revenueProfitChart" height="300"></canvas>
                    </div>
                    <div class="mt-3">
                        <h6 class="mb-2">Doanh thu theo danh mục</h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Danh mục</th>
                                    <th>Doanh thu (VNĐ)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($revenueByCategory as $category)
                                    <tr>
                                        <td>{{ $category->category_name }}</td>
                                        <td>{{ number_format($category->revenue, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
            color: #555;
        }

        .card-title {
            font-size: 1.25rem;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const period = '{{ $period }}';
        let chartLabels = [];

        if (period === 'day') {
            chartLabels = Array.from({
                length: 24
            }, (_, i) => `${i}h`);
        } else if (period === 'week') {
            chartLabels = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'CN'];
        } else if (period === 'month') {
            chartLabels = Array.from({
                length: 30
            }, (_, i) => `Ngày ${i+1}`);
        } else {
            chartLabels = ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'];
        }

        const usersData = {
            totalUsers: {{ $totalUsers ?? 0 }},
            usersThisPeriod: {{ $usersThisPeriod ?? 0 }},
            usersLastPeriod: {{ $usersLastPeriod ?? 0 }}
        };

        const ordersData = {
            totalOrders: {{ $totalOrders ?? 0 }},
            totalRevenue: {{ $totalRevenue ?? 0 }},
            totalStock: {{ $totalStock ?? 0 }},
            totalInventoryValue: {{ $totalInventoryValue ?? 0 }}
        };

        const usersCtx = document.getElementById('usersChart').getContext('2d');
        new Chart(usersCtx, {
            type: 'bar',
            data: {
                labels: [
                    '{{ $period == 'day' ? 'Hôm qua' : ($period == 'week' ? 'Tuần trước' : ($period == 'month' ? 'Tháng trước' : 'Năm trước')) }}',
                    '{{ $period == 'day' ? 'Hôm nay' : ($period == 'week' ? 'Tuần này' : ($period == 'month' ? 'Tháng này' : 'Năm này')) }}',
                    'Tổng cộng'
                ],
                datasets: [{
                    label: 'Số người dùng',
                    data: [usersData.usersLastPeriod, usersData.usersThisPeriod, usersData.totalUsers],
                    backgroundColor: ['#ffccbc', '#ff5722', '#d81b60'],
                    borderColor: ['#ff8a65', '#e64a19', '#ad1457'],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true
                    },
                    tooltip: {
                        bodyFont: {
                            size: 10
                        }
                    }
                },
                maintainAspectRatio: false
            }
        });

        const ordersCtx = document.getElementById('ordersChart').getContext('2d');
        new Chart(ordersCtx, {
            type: 'bar',
            data: {
                labels: ['Đơn hàng', 'Doanh thu', 'Tồn kho', 'Giá trị tồn kho'],
                datasets: [{
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
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        ticks: {
                            font: {
                                size: 10
                            }
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: {
                                size: 10
                            }
                        }
                    },
                    tooltip: {
                        bodyFont: {
                            size: 10
                        }
                    }
                },
                maintainAspectRatio: false
            }
        });

        const revenueProfitCtx = document.getElementById('revenueProfitChart').getContext('2d');
        new Chart(revenueProfitCtx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: @json($monthlyRevenueData),
                        backgroundColor: 'rgba(255, 87, 34, 0.2)',
                        borderColor: '#ff5722',
                        borderWidth: 2,
                        fill: true
                    },
                    {
                        label: 'Lợi nhuận (VNĐ)',
                        data: @json($monthlyProfitData),
                        backgroundColor: 'rgba(34, 139, 34, 0.2)',
                        borderColor: '#228B22',
                        borderWidth: 2,
                        fill: true
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: {
                                size: 10
                            }
                        }
                    },
                    tooltip: {
                        bodyFont: {
                            size: 10
                        }
                    }
                },
                maintainAspectRatio: false
            }
        });
    </script>
@endsection
