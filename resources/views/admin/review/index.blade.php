@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2>{{ $title }}</h2>

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

        <form method="GET" action="{{ route('admin.review.index') }}" id="filterForm">
            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="categories_id">Danh mục:</label>
                    <select name="categories_id" id="categories_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Tất cả</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $category->id == request('categories_id') ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="sort_by">Sắp xếp:</label>
                    <select name="sort_by" id="sort_by" class="form-select" onchange="this.form.submit()">
                        <option value="created_at" {{ $sortBy == 'created_at' && $sortOrder == 'desc' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="created_at" {{ $sortBy == 'created_at' && $sortOrder == 'asc' ? 'selected' : '' }}>Cũ nhất</option>
                    </select>
                    <input type="hidden" name="sort_order" id="sort_order" value="{{ $sortOrder }}">
                </div>
            </div>
        </form>

        <table class="table table-bordered mt-3">
            <thead class="text-center">
                <tr>
                    <th>#</th>
                    <th>Ảnh sản phẩm</th>
                    <th>Tên sản phẩm</th>
                    <th>Người đánh giá</th>
                    <th>Điểm</th>
                    <th>Lượt thích</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($reviews as $review)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if ($review->product && $review->product->image)
                                <img src="{{ asset('storage/' . $review->product->image) }}" width="50" alt="{{ $review->product->name }}">
                            @else
                                <img src="{{ asset('images/default-product.jpg') }}" width="50" alt="No Image">
                            @endif
                        </td>
                        <td>{{ $review->product ? $review->product->name : 'N/A' }}</td>
                        <td>{{ $review->user ? $review->user->name : 'N/A' }}</td>
                        <td>{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</td>
                        <td>{{ $review->likes->count() }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#reviewModal" data-review='{{ json_encode($review->toArray()) }}'>
                                <i class="fa-solid fa-eye"></i> Xem chi tiết
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $reviews->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reviewModalLabel">Chi tiết đánh giá</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img id="modalProductImage" src="" alt="Product Image" class="img-fluid" style="max-height: 150px; object-fit: cover;">
                        </div>
                        <div class="col-md-8">
                            <p><strong>Sản phẩm:</strong> <span id="modalProductName"></span></p>
                            <p><strong>Người đánh giá:</strong> <span id="modalUserName"></span></p>
                            <p><strong>Điểm:</strong> <span id="modalRating"></span></p>
                            <p><strong>Bình luận:</strong> <span id="modalComment"></span></p>
                            <p><strong>Phản hồi từ cửa hàng:</strong> <span id="modalStoreReply"></span></p>
                            <div id="modalImages" class="d-flex flex-wrap gap-2"></div>
                        </div>
                    </div>
                    <hr>
                    <!-- Form để phản hồi -->
                    <form id="replyForm" method="POST" action="{{ route('admin.review.reply') }}">
                        @csrf
                        <input type="hidden" name="review_id" id="reviewId">
                        <div class="mb-3">
                            <label for="reply" class="form-label"><strong>Phản hồi từ cửa hàng</strong></label>
                            <textarea name="reply" id="reply" class="form-control" rows="3" placeholder="Nhập phản hồi của bạn..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Gửi phản hồi</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('reviewModal');
            modal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget; // Nút "Xem chi tiết"
                const review = JSON.parse(button.getAttribute('data-review')); // Dữ liệu review

                // Điền thông tin chi tiết đánh giá
                document.getElementById('modalProductName').textContent = review.product ? review.product.name : 'N/A';
                document.getElementById('modalUserName').textContent = review.user ? review.user.name : 'N/A';
                document.getElementById('modalRating').innerHTML = '★'.repeat(review.rating) + '☆'.repeat(5 - review.rating);

                // Xử lý bình luận và phản hồi từ cửa hàng
                const comment = review.comment || 'Không có bình luận';
                const storeReplyRegex = /\n\n\[Phản hồi từ cửa hàng - (.+?)\]: (.*)/s;
                const match = comment.match(storeReplyRegex);
                if (match) {
                    document.getElementById('modalComment').textContent = comment.replace(storeReplyRegex, '').trim();
                    document.getElementById('modalStoreReply').textContent = match[2] || 'Không có phản hồi';
                    document.getElementById('reply').value = match[2]; // Điền phản hồi vào textarea để chỉnh sửa
                } else {
                    document.getElementById('modalComment').textContent = comment;
                    document.getElementById('modalStoreReply').textContent = 'Không có phản hồi';
                    document.getElementById('reply').value = ''; // Để trống textarea nếu chưa có phản hồi
                }

                // Hiển thị ảnh sản phẩm
                const productImage = document.getElementById('modalProductImage');
                productImage.src = review.product && review.product.image 
                    ? '{{ asset('storage') }}/' + review.product.image 
                    : '{{ asset('images/default-product.jpg') }}';

                // Hiển thị ảnh từ đánh giá
                const imagesContainer = document.getElementById('modalImages');
                imagesContainer.innerHTML = '';
                if (review.images && review.images.length > 0) {
                    review.images.forEach(image => {
                        const img = document.createElement('img');
                        img.src = '{{ asset('storage') }}/' + image;
                        img.alt = 'Review Image';
                        img.className = 'img-fluid';
                        img.style.maxWidth = '100px';
                        img.style.maxHeight = '100px';
                        img.style.objectFit = 'cover';
                        imagesContainer.appendChild(img);
                    });
                } else {
                    imagesContainer.textContent = 'Không có ảnh';
                }

                // Cập nhật review_id trong form
                document.getElementById('reviewId').value = review.id;
            });

            // Handle sort_by and sort_order
            document.getElementById('sort_by').addEventListener('change', function() {
                document.getElementById('sort_order').value = this.value === 'created_at' && this.selectedOptions[0].text === 'Mới nhất' ? 'desc' : 'asc';
                document.getElementById('filterForm').submit();
            });
        });
    </script>
@endsection