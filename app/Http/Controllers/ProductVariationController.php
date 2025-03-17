<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductVariationController extends Controller
{
    public function store (Request $request)
    {
        $request->validate([
            'size' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($request->has('variations')){
            foreach ($request->input('variations') as $index => $variation) {
                $variationImagePath = null;
                if($request->hasFile('variations.'.$index.'.image')){
                    $variationImagePath = $request->file('variations.'.$index.'.image')->store('variations', 'public');
                }

                DB::table('product_variations')->insert([
                    'product_id' => $request->product_id,
                    'size' => $variation['size'],
                    'color' => $variation['color'],
                    'price' => $variation['price'],
                    'stock' => $variation['stock'],
                    'image' => $variationImagePath,
                    'sku' => Str::slug($request->product_id.'-'.$variation['size'].'-'.$variation['color']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('admin.product.index')->with('success', 'Thêm biến thể thành công.');
    }

    public function update (Request $request, $product_id){
        $request->validate([
            'size' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($request->has('variations')){
            foreach ($request->input('variations') as $index => $variation) {
                $variationImagePath = null;
                if($request->hasFile('variations.'.$index.'.image')){
                    $variationImagePath = $request->file('variations.'.$index.'.image')->store('variations', 'public');
                }

                DB::table('product_variations')->where('id', $variation['id'])->update([
                    'size' => $variation['size'],
                    'color' => $variation['color'],
                    'price' => $variation['price'],
                    'stock' => $variation['stock'],
                    'image' => $variationImagePath,
                    'sku' => Str::slug($product_id.'-'.$variation['size'].'-'.$variation['color']),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->route('admin.product.index')->with('success', 'Cập nhật biến thể thành công.');

    }

    public function delete($productId){
        $varionts = DB::table('product_variations')->where('product_id', $productId)->get();
        foreach ($varionts as $variont) {
            if($variont->image){
                Storage::disk('public')->delete($variont->image);
            }
        }
        DB::table('product_variations')->where('product_id', $productId)->delete();
        return redirect()->route('admin.product.index')->with('success', 'Xóa biến thể thành công.');
    }

}

