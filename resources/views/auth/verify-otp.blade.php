@extends('layouts.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-3 overflow-hidden">
                <div class="card-header bg-gradient-orange text-white text-center py-4">
                    <h4 class="mb-0 text-orange fw-bold">Xác Nhận Mã OTP</h4>
                </div>
                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.validate-otp') }}">
                        @csrf
                        <div class="mb-4">
                            <label for="otp" class="form-label fw-bold">Mã OTP</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-key"></i></span>
                                <input type="text" 
                                       class="form-control @error('otp') is-invalid @enderror" 
                                       id="otp" 
                                       name="otp" 
                                       required 
                                       maxlength="6" 
                                       pattern="\d{6}" 
                                       placeholder="Nhập 6 chữ số"
                                       autofocus>
                            </div>
                            @error('otp')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                            <button type="submit" class="btn btn-orange bg-gradient-orange">
                                <i class="fas fa-check me-2"></i>Xác Nhận
                            </button>
                            <a href="{{ route('password.forgot') }}" 
                               class="btn btn-outline-orange">
                                <i class="fas fa-redo me-2"></i>Gửi lại mã OTP
                            </a>
                        </div>
                    </form>

                    <div class="mt-4 text-muted text-center">
                        <small>
                            <div>* Mã OTP có hiệu lực trong 15 phút</div>
                            <div>* Mã chỉ được sử dụng một lần</div>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection