<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Category;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function listReviews(Request $request)
    {
        $title = 'Quản lý đánh giá';
        $sortBy = $request->input('sort_by', 'created_at'); 
        $sortOrder = $request->input('sort_order', 'desc');
        $categoryId = $request->input('categories_id'); 
        $productId = $request->input('product_id'); 
        $query = ProductReview::with('user', 'product.category');

        if ($categoryId) {
            $query->whereHas('product', function ($q) use ($categoryId) {
                $q->where('categories_id', $categoryId);
            });
        }
        if ($productId) {
            $query->where('product_id', $productId);
        }

        $query->orderBy($sortBy, $sortOrder);

        $reviews = $query->paginate(10);
        $reviews->appends($request->except('page'));

        $categories = Category::all();
        $products = Product::all();

        return view('admin.review.index', compact('title', 'reviews', 'categories', 'products', 'sortBy', 'sortOrder'));
    }

    public function reply(Request $request)
    {
        $request->validate([
            'review_id' => 'required|exists:product_reviews,id', // Đảm bảo review_id hợp lệ
            'reply' => 'required|string|max:1000',
        ]);

        $review = ProductReview::findOrFail($request->review_id);
        $timestamp = now()->format('d/m/Y H:i');
        $storeReply = "\n\n[Phản hồi từ cửa hàng - $timestamp]: " . $request->input('reply');

        // Gắn phản hồi vào bình luận hiện tại
        $review->comment = $review->comment ? $review->comment . $storeReply : $storeReply;
        $review->save();

        return redirect()->route('admin.review.index')->with('success', 'Phản hồi đã được gửi thành công!');
    }
}