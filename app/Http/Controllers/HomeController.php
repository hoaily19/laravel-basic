<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
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

    public function product()
    {
        $query = DB::table('products');
        if (request()->has('search')) {
            $search = request()->input('search');
            $query->where('name', 'like', '%' . $search . '%');
        }
        $title = 'Sản phẩm';
        $products = DB::table('products')->paginate(15);
        $categories = Category::with('brands')->get();
        return view('product', compact('products', 'categories', 'title'));
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
}
