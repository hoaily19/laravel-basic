@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Danh sách sản phẩm</h2>

    <!-- Notifications -->
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

    <a href="{{ route('admin.product.create') }}" class="btn btn-primary mb-3">Thêm sản phẩm</a>
        <div class="mb-3">
        <select id="sort" class="form-select w-auto d-inline-block" onchange="sortProducts(this.value)">
            <option value="id_desc" {{ $sort === 'id_desc' ? 'selected' : '' }}>Mới nhất</option>
            <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
            <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
            <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Tên: A-Z</option>
            <option value="name_desc" {{ $sort === 'name_desc' ? 'selected' : '' }}>Tên: Z-A</option>
            <option value="stock_asc" {{ $sort === 'stock_asc' ? 'selected' : '' }}>Số lượng: Thấp đến Cao</option>
            <option value="stock_desc" {{ $sort === 'stock_desc' ? 'selected' : '' }}>Số lượng: Cao đến Thấp</option>
        </select>
    </div>

    <!-- Product Table -->
    <table class="table table-bordered">
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
            @if ($products->isEmpty())
                <tr>
                    <td colspan="7" class="text-center">Không tìm thấy sản phẩm nào.</td>
                </tr>
            @else
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" width="50" alt="{{ $product->name }}">
                            @else
                                <span>Không có ảnh</span>
                            @endif
                        </td>
                        <td>{{ number_format($product->price, 0) }} VNĐ</td>
                        <td class="text-danger">{{ number_format($product->price - $product->original_price, 0) }} VNĐ</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            <a href="{{ route('admin.product.edit', $product->id) }}" class="btn btn-sm btn-warning">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="{{ route('admin.product.delete', $product->id) }}" class="btn btn-sm btn-danger"
                               onclick="return confirm('Bạn có chắc muốn xóa sản phẩm {{ $product->name }}?')">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>

<script>
    function sortProducts(sortType) {
        const url = new URL(window.location.href);
        url.searchParams.set('sort', sortType);
        window.location.href = url.toString();
    }
</script>
@endsection