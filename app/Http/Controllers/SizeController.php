<?php

namespace App\Http\Controllers;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $title = 'Quản lí size';
    //     $sizes = Size::all();
    //     return view('admin.variants.size.index', compact('sizes', 'title'));
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Thêm size';
        return view('admin.variants.size.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:sizes,name|max:255',
        ],[
            'name.unique' => 'Size đã tồn tại!',
        ]);

        Size::create([
            'name' => $request->name
        ]);
        return redirect()->route('variants.index')->with('success', 'Created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $id)
    {
        $title = 'Sửa size';
        $size = Size::findOrFail($id);
        return view('admin.variants.size.edit', compact('size', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $size = Size::findOrFail($id);  
        $size->update([
            'name' => $request->name
        ]);

        return redirect()->route('variants.index')->with('success', 'Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $size = Size::findOrFail($id);
        $size->delete();
        return redirect()->route('variants.index')->with('success', 'Deleted successfully.');
    }
}
