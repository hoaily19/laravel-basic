@extends('layouts.admin')

@section('title', 'Chỉnh sửa mã giảm giá')

@section('content')
    <div class="container my-4">
        <h2 class="mb-4">Chỉnh sửa mã giảm giá</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.coupon.update', $coupon->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="code" class="form-label">Mã giảm giá <span class="text-danger">*</span></label>
                <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" 
                       value="{{ old('code', $coupon->code) }}" placeholder="Nhập mã (ví dụ: SALE20)" required>
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="discount" class="form-label">Giá trị giảm <span class="text-danger">*</span></label>
                <input type="number" name="discount" id="discount" class="form-control @error('discount') is-invalid @enderror" 
                       value="{{ old('discount', $coupon->discount) }}" step="0.01" min="0" placeholder="Ví dụ: 10 hoặc 50000" required>
                @error('discount')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Loại giảm giá <span class="text-danger">*</span></label>
                <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                    <option value="" disabled>Chọn loại</option>
                    <option value="percentage" {{ old('type', $coupon->type) === 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                    <option value="fixed" {{ old('type', $coupon->type) === 'fixed' ? 'selected' : '' }}>Số tiền cố định (VNĐ)</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="min_order_amount" class="form-label">Đơn hàng tối thiểu (VNĐ)</label>
                <input type="number" name="min_order_amount" id="min_order_amount" 
                       class="form-control @error('min_order_amount') is-invalid @enderror" 
                       value="{{ old('min_order_amount', $coupon->min_order_amount) }}" min="0" step="1" placeholder="Ví dụ: 100000">
                <small class="form-text text-muted">Để trống nếu không yêu cầu đơn hàng tối thiểu.</small>
                @error('min_order_amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="max_uses" class="form-label">Số lần sử dụng tối đa</label>
                <input type="number" name="max_uses" id="max_uses" class="form-control @error('max_uses') is-invalid @enderror" 
                       value="{{ old('max_uses', $coupon->max_uses) }}" min="1" step="1" placeholder="Ví dụ: 100">
                <small class="form-text text-muted">Để trống nếu không giới hạn số lần sử dụng.</small>
                @error('max_uses')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="start_date" class="form-label">Ngày bắt đầu</label>
                <input type="datetime-local" name="start_date" id="start_date" 
                       class="form-control @error('start_date') is-invalid @enderror" 
                       value="{{ old('start_date', $coupon->start_date ? $coupon->start_date->format('Y-m-d\TH:i') : '') }}">
                <small class="form-text text-muted">Để trống nếu mã có hiệu lực ngay lập tức.</small>
                @error('start_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="expires_at" class="form-label">Ngày hết hạn</label>
                <input type="datetime-local" name="expires_at" id="expires_at" 
                       class="form-control @error('expires_at') is-invalid @enderror" 
                       value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}">
                <small class="form-text text-muted">Để trống nếu mã không có hạn sử dụng.</small>
                @error('expires_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" 
                       value="1" {{ old('is_active', $coupon->is_active) == 1 ? 'checked' : '' }}>
                <label for="is_active" class="form-check-label">Kích hoạt mã giảm giá</label>
                <small class="form-text text-muted">Bỏ chọn để vô hiệu hóa mã giảm giá.</small>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Cập nhật mã giảm giá</button>
                <a href="{{ route('admin.coupon.index') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </form>
    </div>
@endsection