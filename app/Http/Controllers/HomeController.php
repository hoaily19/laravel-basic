<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Show the homepage.
     *
     * @return \Illuminate\View\View
     */

    public function index()
    {
        $query = DB::table('products');

        if (request()->has('search')) {
            $search = request()->input('search');
            $query->where('name', 'like', '%' . $search . '%');
        }
        $title = 'Sản phẩm';
        $products = $query->paginate(5);
        $categories = Category::with('brands')->get();
        return view('index', compact('products', 'categories', 'title'));
    }

    public function product(Request $request)
    {
        $query = DB::table('products');
        // Tìm kiếm theo tên sản phẩm
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%');
        }
        if ($request->has('categories')) {
            $categories = $request->input('categories');
            if (!empty($categories)) {
                $query->whereIn('categories_id', $categories);
            }
        }
        // Lọc theo thương hiệu
        if ($request->has('brands')) {
            $brands = $request->input('brands');
            if (!empty($brands)) {
                $query->whereIn('brand_id', $brands);
            }
        }
        // Lọc theo khoảng giá
        if ($request->has('price_range')) {
            $priceRanges = $request->input('price_range');
            $query->where(function ($q) use ($priceRanges) {
                foreach ($priceRanges as $range) {
                    if ($range == 'under_500k') {
                        $q->orWhere('price', '<', 500000);
                    } elseif ($range == '500k_1m') {
                        $q->orWhereBetween('price', [500000, 1000000]);
                    } elseif ($range == '1m_2m') {
                        $q->orWhereBetween('price', [1000000, 2000000]);
                    } elseif ($range == '2m_5m') {
                        $q->orWhereBetween('price', [2000000, 5000000]);
                    } elseif ($range == 'above_5m') {
                        $q->orWhere('price', '>', 5000000);
                    }
                }
            });
        }
        // Sắp xếp
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
            }
        }
        $products = $query->paginate(15);
        $categories = Category::with('brands')
            ->orderBy('created_at', 'desc')
            ->get();
        $brands = DB::table('brand')
            ->orderBy('created_at', 'desc')
            ->get();
        $title = 'Sản phẩm';
        return view('product', compact('products', 'categories', 'title', 'brands'));
    }

    public function show($slug)
    {
        $product = DB::table('products')->where('slug', $slug)->first();
        $variations = DB::table('product_variations')
            ->where('product_id', $product->id)
            ->join('sizes', 'product_variations.size_id', '=', 'sizes.id', 'left')
            ->join('colors', 'product_variations.color_id', '=', 'colors.id', 'left')
            ->select('product_variations.*', 'sizes.name as size_name', 'colors.name as color_name')
            ->get();
        $category = DB::table('categories')->where('id', $product->categories_id)->first();
        $brand = DB::table('brand')->where('id', $product->brand_id)->first();
        return view('detail', compact('product', 'variations', 'category', 'brand'));
    }

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

        $user_id = Auth::user()->id ?? null;

        $carts = Cart::with(['product', 'variation'])
        ->where('user_id', $user_id)
        ->get();
        $addresses = Address::where('user_id', Auth::user()->id)->get();
        return view('checkout', compact('title', 'addresses', 'carts'));
    }
}
