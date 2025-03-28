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

    .btn-add {
        background-color: #ff6200;
        color: #fff;
    }

    .btn-add:hover {
        background-color: #e55a00;
        color: #fff;
    }

    .address-card {
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
    }

    .address-card.default {
        border-color: #ff6200;
        background-color: #fff5f0;
    }
</style>

<div class="container mt-3">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile') }}">Thông tin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('profile.address') }}">Địa Chỉ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.changePassword') }}">Đổi mật khẩu</a>
                </li>
            </ul>
        </div>

        <!-- Main content -->
        <div class="col-md-9 col-lg-10 profile-content">
            <div class="profile-header">
                @if(Auth::user()->avatar)
                    <img src="{{ asset(Auth::user()->avatar) }}" alt="Avatar">
                @else
                    <img src="https://fullstack.edu.vn/assets/f8-icon-lV2rGpF0.png" alt="Avatar">
                @endif
                <h2>{{ Auth::user()->username }}</h2>
            </div>

            <div class="mb-3">
                <button type="button" class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                    Thêm địa chỉ mới
                </button>
            </div>

            <!-- Address List -->
            <div class="address-list">
                @forelse($addresses as $address)
                    <div class="address-card {{ $address->is_default ? 'default' : '' }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-1"><strong>{{ $address->receiver_name }}</strong> | {{ $address->phone }}</p>
                                <p class="mb-1">{{ $address->street }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}</p>
                                @if($address->is_default)
                                    <span class="badge bg-success">Mặc định</span>
                                @endif
                            </div>
                            <form action="{{ route('profile.deleteAddress', $address->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('Bạn có chắc muốn xóa địa chỉ này?')">
                                        <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p>Chưa có địa chỉ nào được thêm.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAddressModalLabel">Thêm địa chỉ mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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

                <form class="profile-form" action="{{ route('profile.storeAddress') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="receiver_name" class="form-label">Tên người nhận</label>
                        <input type="text" class="form-control @error('receiver_name') is-invalid @enderror" id="receiver_name" name="receiver_name" value="{{ old('receiver_name') }}">
                        @error('receiver_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="province" class="form-label">Tỉnh/Thành phố</label>
                        <select class="form-control @error('province') is-invalid @enderror" id="province" name="province">
                            <option value="">Chọn tỉnh/thành phố</option>
                        </select>
                        @error('province')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="district" class="form-label">Quận/Huyện</label>
                        <select class="form-control @error('district') is-invalid @enderror" id="district" name="district" disabled>
                            <option value="">Chọn quận/huyện</option>
                        </select>
                        @error('district')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="ward" class="form-label">Xã/Phường</label>
                        <select class="form-control @error('ward') is-invalid @enderror" id="ward" name="ward" disabled>
                            <option value="">Chọn xã/phường</option>
                        </select>
                        @error('ward')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="street" class="form-label">Địa chỉ cụ thể (số nhà, đường)</label>
                        <input type="text" class="form-control @error('street') is-invalid @enderror" id="street" name="street" value="{{ old('street') }}">
                        @error('street')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1">
                        <label class="form-check-label" for="is_default">Đặt làm địa chỉ mặc định</label>
                    </div>
                    
                    <button type="submit" class="btn btn-orange">Thêm địa chỉ</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Load provinces
    fetch('https://provinces.open-api.vn/api/p/')
        .then(response => response.json())
        .then(data => {
            const provinceSelect = document.getElementById('province');
            data.forEach(province => {
                const option = document.createElement('option');
                option.value = province.name;
                option.text = province.name;
                provinceSelect.appendChild(option);
            });
        });

    // Load districts when a province is selected
    document.getElementById('province').addEventListener('change', function() {
        const provinceName = this.value;
        const districtSelect = document.getElementById('district');
        const wardSelect = document.getElementById('ward');

        districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
        wardSelect.innerHTML = '<option value="">Chọn xã/phường</option>';
        districtSelect.disabled = true;
        wardSelect.disabled = true;

        if (provinceName) {
            fetch(`https://provinces.open-api.vn/api/p/search/?q=${provinceName}`)
                .then(response => response.json())
                .then(provinces => {
                    const province = provinces.find(p => p.name === provinceName);
                    if (province) {
                        fetch(`https://provinces.open-api.vn/api/p/${province.code}?depth=2`)
                            .then(response => response.json())
                            .then(data => {
                                districtSelect.disabled = false;
                                data.districts.forEach(district => {
                                    const option = document.createElement('option');
                                    option.value = district.name;
                                    option.text = district.name;
                                    districtSelect.appendChild(option);
                                });
                            });
                    }
                });
        }
    });

    // Load wards when a district is selected
    document.getElementById('district').addEventListener('change', function() {
        const districtName = this.value;
        const provinceName = document.getElementById('province').value;
        const wardSelect = document.getElementById('ward');

        wardSelect.innerHTML = '<option value="">Chọn xã/phường</option>';
        wardSelect.disabled = true;

        if (districtName) {
            fetch(`https://provinces.open-api.vn/api/p/search/?q=${provinceName}`)
                .then(response => response.json())
                .then(provinces => {
                    const province = provinces.find(p => p.name === provinceName);
                    if (province) {
                        fetch(`https://provinces.open-api.vn/api/d/search/?q=${districtName}&p=${province.code}`)
                            .then(response => response.json())
                            .then(districts => {
                                const district = districts.find(d => d.name === districtName);
                                if (district) {
                                    fetch(`https://provinces.open-api.vn/api/d/${district.code}?depth=2`)
                                        .then(response => response.json())
                                        .then(data => {
                                            wardSelect.disabled = false;
                                            data.wards.forEach(ward => {
                                                const option = document.createElement('option');
                                                option.value = ward.name;
                                                option.text = ward.name;
                                                wardSelect.appendChild(option);
                                            });
                                        });
                                }
                            });
                    }
                });
        }
    });
</script>
@endsection