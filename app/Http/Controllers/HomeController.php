<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Models\Brands;
use App\Models\Cart;
use App\Models\Address;
use App\Models\ProductReview;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $query = Product::query();

        if (request()->has('search')) {
            $search = request()->input('search');
            $query->where('name', 'like', '%' . $search . '%');
        }

        $title = 'Sản phẩm';
        $products = $query->paginate(5);
        $categories = Category::with('brands')->take(3)->get();

        $categoryProducts = [];
        foreach ($categories as $category) {
            $categoryProducts[$category->id] = Product::where('categories_id', $category->id)
                ->take(5)
                ->get();
        }

        return view('index', compact('products', 'categories', 'categoryProducts', 'title'));
    }

    public function product(Request $request)
{
    $query = Product::query();

    // Search by product name
    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where('name', 'like', '%' . $search . '%');
    }

    // Filter by categories
    if ($request->has('categories')) {
        $categories = $request->input('categories');
        if (!empty($categories)) {
            $query->whereIn('categories_id', $categories);
        }
    }

    // Filter by brands
    if ($request->has('brands')) {
        $brands = $request->input('brands');
        if (!empty($brands)) {
            $query->whereIn('brand_id', $brands);
        }
    }

    // Filter by dynamic price range
    if ($request->has('price_min') && $request->has('price_max')) {
        $priceMin = $request->input('price_min');
        $priceMax = $request->input('price_max');
        if ($priceMin >= 0 && $priceMax >= $priceMin) {
            $query->whereBetween('price', [$priceMin, $priceMax]);
        }
    }

    // Filter by attributes (e.g., color, size)
    if ($request->has('attributes')) {
        $attributes = $request->input('attributes');
        foreach ($attributes as $attributeId => $values) {
            if (!empty($values)) {
                $query->whereHas('attributes', function ($q) use ($attributeId, $values) {
                    $q->where('attribute_id', $attributeId)->whereIn('value', $values);
                });
            }
        }
    }

    // Filter by rating
    if ($request->has('rating') && $request->input('rating') > 0) {
        $rating = $request->input('rating');
        $query->where('average_rating', '>=', $rating);
    }

    // Filter by stock availability
    if ($request->has('in_stock') && $request->input('in_stock') == '1') {
        $query->where('stock', '>', 0);
    }

    // Sorting
    if ($request->has('sort')) {
        $sort = $request->input('sort');
        if ($sort == 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort == 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort == 'newest') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sort == 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort == 'rating_desc') {
            $query->orderBy('average_rating', 'desc');
        }
    }

    $products = $query->paginate(15);

    // Fetch filter data
    $categories = Category::with('brands')->orderBy('created_at', 'desc')->get();
    $brands = Brands::orderBy('created_at', 'desc')->get();
    $maxPrice = Product::max('price') ?: 10000000;
    $minPrice = Product::min('price') ?: 0;
    $title = 'Sản phẩm';

    return view('product', compact('products', 'categories', 'brands', 'minPrice', 'maxPrice', 'title'));
}

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $variations = $product->variations()
            ->with(['size', 'color'])
            ->get()
            ->map(function ($variation) {
                return (object) [
                    'id' => $variation->id,
                    'product_id' => $variation->product_id,
                    'size_id' => $variation->size_id,
                    'color_id' => $variation->color_id,
                    'price' => $variation->price,
                    'stock' => $variation->stock,
                    'image' => $variation->image,
                    'size_name' => $variation->size ? $variation->size->name : null,
                    'color_name' => $variation->color ? $variation->color->name : null,
                ];
            });

        $category = Category::find($product->categories_id);
        $brand = Brands::find($product->brand_id);

        $relatedProducts = Product::where('categories_id', $product->categories_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', 1)
            ->limit(4)
            ->get();

        $reviews = ProductReview::where('product_id', $product->id)
            ->with(['user', 'likes'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        $totalReviews = ProductReview::where('product_id', $product->id)->count();
        $averageRating = ProductReview::where('product_id', $product->id)->avg('rating') ?? 0;

        $fiveStarReviews = ProductReview::where('product_id', $product->id)
            ->where('rating', 5)
            ->count();
        $fourStarReviews = ProductReview::where('product_id', $product->id)
            ->where('rating', 4)
            ->count();
        $threeStarReviews = ProductReview::where('product_id', $product->id)
            ->where('rating', 3)
            ->count();
        $twoStarReviews = ProductReview::where('product_id', $product->id)
            ->where('rating', 2)
            ->count();
        $oneStarReviews = ProductReview::where('product_id', $product->id)
            ->where('rating', 1)
            ->count();
        $withComments = ProductReview::where('product_id', $product->id)
            ->whereNotNull('comment')
            ->count();
        $withMedia = ProductReview::where('product_id', $product->id)
            ->where(function ($query) {
                $query->where('has_image', true)
                    ->orWhere('has_video', true);
            })
            ->count();

        $userReview = Auth::check() ? ProductReview::where('product_id', $product->id)
            ->where('user_id', Auth::id())
            ->first() : null;

        $cartQuantity = Auth::check() ? Cart::where('user_id', Auth::id())->sum('quantity') : 0;

        $product->increment('view_count');

        return view('detail', compact(
            'product',
            'variations',
            'category',
            'brand',
            'relatedProducts',
            'reviews',
            'averageRating',
            'totalReviews',
            'fiveStarReviews',
            'fourStarReviews',
            'threeStarReviews',
            'twoStarReviews',
            'oneStarReviews',
            'withComments',
            'withMedia',
            'userReview',
            'cartQuantity'
        ));
    }

    //review
    public function storeReview(Request $request, $slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $existingReview = ProductReview::where('product_id', $product->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi!');
        }

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('images/reviews', 'public');
                $images[] = $path;
            }
        }

        ProductReview::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'images' => $images,
            'has_image' => !empty($images),
            'has_video' => false,
        ]);

        return redirect()->back()->with('success', 'Đánh giá của bạn đã được gửi thành công!');
    }

    public function updateReview(Request $request, $reviewId)
    {
        $review = ProductReview::findOrFail($reviewId);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|max:2048',
            'existing_images' => 'nullable|string',
        ]);

        $review->rating = $validated['rating'];
        $review->comment = $validated['comment'] ?? $review->comment;

        $existingImages = $request->input('existing_images') ? json_decode($request->input('existing_images'), true) : $review->images;
        $newImages = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews', 'public');
                $newImages[] = $path;
            }
            $review->images = array_merge($existingImages ?? [], $newImages);
        } else {
            $review->images = $existingImages;
        }

        $review->save();

        return redirect()->back()->with('success', 'Đánh giá đã được cập nhật thành công!');
    }

    public function deleteReview($id)
    {
        $review = ProductReview::findOrFail($id);

        if ($review->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa đánh giá này!');
        }

        if ($review->images) {
            foreach ($review->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $review->delete();

        return redirect()->back()->with('success', 'Đánh giá của bạn đã được xóa!');
    }

    public function like(ProductReview $review) // Sửa thành ProductReview
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thích đánh giá!');
        }

        if ($review->likes()->where('user_id', $user->id)->exists()) {
            $review->likes()->detach($user->id); // Bỏ thích
            $message = 'Đã bỏ thích đánh giá!';
        } else {
            $review->likes()->attach($user->id); // Thích
            $message = 'Đã thích đánh giá!';
        }

        return redirect()->back()->with('success', $message);
    }

    //variations
    public function variations()
    {
        $title = 'Quản lý biến thể';
        $sizes = Size::all();
        $colors = Color::all();
        return view('admin.variants.index', compact('sizes', 'colors', 'title'));
    }

    public function checkout()
    {
        $title = "Thanh toán";
        $user_id = Auth::id();
        $carts = Cart::with(['product', 'variation'])
            ->where('user_id', $user_id)
            ->get();
        $addresses = Address::where('user_id', $user_id)->get();
        $cartQuantity = $carts->sum('quantity');

        return view('checkout', compact('title', 'addresses', 'carts', 'cartQuantity'));
    }

    //favorite
    public function toggleFavorite(Request $request)
    {
        $user = Auth::user();
        $productId = $request->input('product_id');

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập để thêm sản phẩm vào danh sách yêu thích.'], 401);
        }

        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Sản phẩm không tồn tại.'], 404);
        }

        if ($user->favorites->contains($productId)) {
            $user->favorites()->detach($productId);
            return response()->json(['success' => true, 'is_favorited' => false, 'message' => 'Đã xóa sản phẩm khỏi danh sách yêu thích.']);
        } else {
            $user->favorites()->attach($productId);
            return response()->json(['success' => true, 'is_favorited' => true, 'message' => 'Đã thêm sản phẩm vào danh sách yêu thích.']);
        }
    }

    public function favorites()
    {
        $user = Auth::user();
        $products = $user->favorites()->paginate(12);
        return view('profile.favorite', compact('products'));
    }
}
