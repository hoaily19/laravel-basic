@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Danh sách mã giảm giá </h2>
    <a href="{{ route('admin.coupon.create') }}" class="btn btn-primary">Thêm mã giảm giá</a>

    @if (session('success'))
        <script>
            iziToast.success({
                title: 'Thành công',
                message: '{{ session('success') }}',
                position: 'topRight'
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            iziToast.error({
                title: 'Lỗi',
                message: '{{ session('error') }}',
                position: 'topRight'
            });
        </script>
    @endif


    <table class="table table-bordered mt-3">
        <thead class="text-center">
            <tr>
                <th>#</th>
                <th>Code</th>
                <th>Giá/Phần trăm giảm</th>
                <th>Loại giảm giá</th>
                <th>Tối thiểu đơn</th>
                <th>Giới hạn dùng</th>
                <th>Số lượng dùng</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày hết hạn</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach ($coupons as $coupon)
            <tr>
                <td>{{ $coupon->id }}</td>
                <td>{{$coupon->code}}</td>
                <td>
                    @if($coupon->type == 'percentage')
                        {{ $coupon->discount }} %
                    @else
                        {{ number_format($coupon->discount) }} VND
                    @endif
                </td>
                <td>
                    @if($coupon->type == 'percentage')
                        Phần trăm
                    @else
                        Tiền cố định
                    @endif
                </td>

                <td>{{ number_format( $coupon->min_order_amount) }}</td>
                <td>{{ $coupon->max_uses }}</td>
                <td>{{ $coupon->used_count }}</td>
                <td>{{ $coupon->created_at }}</td>
                <td>{{ $coupon->expires_at }}</td>
                <td>
                    <a href="{{ route('admin.coupon.edit', $coupon->id) }}" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen-to-square"></i></a> |
                    <form action="{{ route('admin.coupon.delete', $coupon->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Bạn có chắc muốn xóa mã giảm giá?')">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
