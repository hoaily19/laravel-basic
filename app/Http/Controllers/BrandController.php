<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Brands;
use App\Models\Category;

class BrandController extends Controller
{
    public function index()
    {
        $title = 'Danh sách thương hiệu';
        $brands = Brands::all();
        $categories = DB::table('categories')->get();
        return view('admin.brand.index', compact('brands', 'categories', 'title'));
    }

    public function create()
    {
        $title = 'Thêm thương hiệu';
        $categories = DB::table('categories')->get();
        return view('admin.brand.create', compact('categories', 'title'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|max:255',
                'categories_id' => 'nullable|exists:categories,id',
                'description' => 'nullable',
                'image' => 'nullable|image|max:2048',
            ],
            [
                'name.required' => 'Tên không được để trống.',
                'name.max' => 'Tên không được quá 255 ký tự.',
                'image.image' => 'Ảnh không đúng định dạng.',
                'image.max' => 'Ảnh không được quá 2048kb.',
            ]
        );

        $imagePath = $request->file('image')->store('brands', 'public');

        $slug = Str::slug($request->name);

        Brands::create([
            'name' => $request->name,
            'slug' => $slug,
            'categories_id' => $request->categories_id,
            'description' => $request->description,
            'image' => $imagePath ?? null,
        ]);

        return redirect()->route('admin.brand.index')->with('success', 'Created successfully.');
    }

    public function edit($id)
    {
        $title = 'Sửa thương hiệu';
        $brand = Brands::findOrFail($id);
        $categories = DB::table('categories')->get();
        return view('admin.brand.edit', compact('brand', 'categories', 'title'));
    }

    public function update(Request $request, $id)
    {
        $brand = Brands::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'categories_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ], [
            'name.required' => 'Tên không được để trống.',
            'name.max' => 'Tên không được quá 255 ký tự.',
            'image.image' => 'Ảnh không đúng định dạng.',
            'image.max' => 'Ảnh không được quá 2048kb.',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('brands', 'public');
            $brand->image = $imagePath;
        }

        $brand->update([
            'name' => $request->name,
            'categories_id' => $request->categories_id,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('admin.brand.index')->with('success', 'Category updated.');
    }

    public function delete($id)
    {
        $brand = Brands::findOrFail($id);
        $brand->delete();
        return redirect()->route('admin.brand.index')->with('success', 'Deleted successfully.');
    }


}
