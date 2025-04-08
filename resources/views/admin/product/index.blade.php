@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Danh sách sản phẩm </h2>
    <a href="{{ route('admin.product.create') }}" class="btn btn-primary">Thêm sản phẩm</a>

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
                <th>Tên Sản Phẩm</th>
                <th>Hình Ảnh</th>
                <th>Giá</th>
                <th>Lợi Nhuận Ước Chừng</th>
                <th>Số Lượng</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>
                    @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" width="50">
                    @endif
                </td>
                <td>{{ number_format($product->price) }} VNĐ</td>
                <td class="text-danger">{{ number_format($product->price-$product->original_price) }} VNĐ</td>
                <td>{{ $product->stock }}</td>
                <td>
                    <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-sm btn-warning"><i class="fa-solid fa-pen-to-square"></i></a> |
                    <a href="{{ route('admin.product.delete', $product->id) }}" class="btn btn-sm btn-danger"
                        onclick="return confirm('Bạn có chắc muốn xóa sản phẩm {{ $product->name }} ?')"> <i class="fa-solid fa-trash"></i></a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        <div class="pagination">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
    
</div>
@endsection
