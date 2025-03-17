<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $query = DB::table('products');

        if (request()->has('search')) {
            $search = request()->input('search');
            $query->where('name', 'like', '%' . $search . '%');
        }

        $products = $query->paginate(5);
        $categories = DB::table('categories')->get();

        return view('index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = DB::table('products')->where('slug', $slug)->first();
        $variations = DB::table('product_variations')->where('product_id', $product->id)->get();

        return view('detail', compact('product', 'variations'));
    }

    public function indexadmin()
    {
        $products = DB::table('products')->paginate(5);
        return view('admin.product.index', compact('products'));
    }

    public function create()
    {
        $title = 'Thêm sản phẩm';
        $categories = DB::table('categories')->get();
        return view('admin.product.create', compact('categories', 'title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'sku' => 'nullable|string|unique:products',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'categories_id' => 'nullable|exists:categories,id',
            'variations.*.size' => 'nullable|string|max:50',
            'variations.*.color' => 'nullable|string|max:50',
            'variations.*.price' => 'nullable|numeric',
            'variations.*.stock' => 'nullable|integer',
            'variations.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ],[
            'name.required' => 'Tên sản phẩm không được để trống',
            'description.required' => 'Mô tả sản phẩm không được để trống',
            'price.required' => 'Giá sản phẩm không được để trống',
            'stock.required' => 'Số lượng tồn kho không được để trống',
            'image.image' => 'Ảnh sản phẩm phải là hình ảnh',
            'image.max' => 'Dung lượng ảnh sản phẩm không được vượt quá 2MB',

        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $additionalImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $additionalImages[] = $path;
            }
        }

        $slug = Str::slug($request->name);

        $productId = DB::table('products')->insertGetId([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'sku' => $request->sku,
            'image' => $imagePath,
            'images' => !empty($additionalImages) ? json_encode($additionalImages) : null,
            'categories_id' => $request->categories_id,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'view_count' => $request->view_count ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($request->has('variations')) {
            foreach ($request->input('variations') as $index => $variation) {
                $variationImagePath = null;
                if ($request->hasFile("variations.$index.image")) {
                    $variationImagePath = $request->file("variations.$index.image")->store('variations', 'public');
                }

                DB::table('product_variations')->insert([
                    'product_id' => $productId,
                    'size' => $variation['size'] ?? null,
                    'color' => $variation['color'] ?? null,
                    'price' => $variation['price'] ?? $request->price, 
                    'stock' => $variation['stock'] ?? 0,
                    'image' => $variationImagePath,
                    'sku' => $variation['sku'] ?? 'VAR-' . $productId . '-' . Str::random(6),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('admin.product.index')->with('success', 'Thêm sản phẩm và biến thể thành công');
    }

    public function edit($id)
    {
        $title = 'Chỉnh sửa sản phẩm';
        $product = DB::table('products')->where('id', $id)->first();
        $categories = DB::table('categories')->get();
        $variations = DB::table('product_variations')->where('product_id', $id)->get();
        return view('admin.product.edit', compact('product', 'categories', 'title', 'variations'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'sku' => 'nullable|string|unique:products,sku,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'categories_id' => 'nullable|exists:categories,id',
            'variations.*.size' => 'nullable|string|max:50',
            'variations.*.color' => 'nullable|string|max:50',
            'variations.*.price' => 'nullable|numeric',
            'variations.*.stock' => 'nullable|integer',
            'variations.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product = DB::table('products')->where('id', $id)->first();

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $additionalImages = $product->images ? json_decode($product->images, true) : [];
        if ($request->hasFile('images')) {
            if (!empty($additionalImages)) {
                foreach ($additionalImages as $oldImage) {
                    if (Storage::disk('public')->exists($oldImage)) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }
            }
            $additionalImages = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $additionalImages[] = $path;
            }
        }

        DB::table('products')->where('id', $id)->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'sku' => $request->sku,
            'image' => $imagePath,
            'images' => !empty($additionalImages) ? json_encode($additionalImages) : null,
            'categories_id' => $request->categories_id,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'view_count' => $request->view_count ?? 0,
            'updated_at' => now(),
        ]);

        if ($request->has('variations')) {
            $oldVariations = DB::table('product_variations')->where('product_id', $id)->get();
            foreach ($oldVariations as $oldVariation) {
                if ($oldVariation->image && Storage::disk('public')->exists($oldVariation->image)) {
                    Storage::disk('public')->delete($oldVariation->image);
                }
            }
            DB::table('product_variations')->where('product_id', $id)->delete();

            foreach ($request->input('variations') as $index => $variation) {
                $variationImagePath = null;
                if ($request->hasFile("variations.$index.image")) {
                    $variationImagePath = $request->file("variations.$index.image")->store('variations', 'public');
                }

                DB::table('product_variations')->insert([
                    'product_id' => $id,
                    'size' => $variation['size'] ?? null,
                    'color' => $variation['color'] ?? null,
                    'price' => $variation['price'] ?? $request->price,
                    'stock' => $variation['stock'] ?? 0,
                    'image' => $variationImagePath,
                    'sku' => $variation['sku'] ?? 'VAR-' . $id . '-' . Str::random(6),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('admin.product.index')->with('success', 'Cập nhật sản phẩm và biến thể thành công');
    }

    public function delete($id)
    {
        $product = DB::table('products')->where('id', $id)->first();
        $variations = DB::table('product_variations')->where('product_id', $id)->get();

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        if ($product->images) {
            $additionalImages = json_decode($product->images, true);
            foreach ($additionalImages as $image) {
                if (Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        foreach ($variations as $variation) {
            if ($variation->image && Storage::disk('public')->exists($variation->image)) {
                Storage::disk('public')->delete($variation->image);
            }
        }

        DB::table('products')->where('id', $id)->delete();

        return redirect()->route('admin.product.index')->with('success', 'Xóa sản phẩm và biến thể thành công');
    }
}