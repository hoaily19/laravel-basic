<?php

namespace App\Http\Controllers;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $title = 'Quản lí color';
    //     $colors = Color::all();
    //     return view('admin.variants.color.index', compact('colors', 'title'));
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Thêm color';
        return view('admin.variants.color.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:colors,name|max:255',
        ],[
            'name.unique' => 'Màu sắc này đã tồn tại!',
        ]);

        Color::create([
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
        $title = 'Sửa color';
        $color = Color::findOrFail($id);
        return view('admin.variants.color.edit', compact('color', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $color = Color::findOrFail($id);  
        $color->update([
            'name' => $request->name
        ]);

        return redirect()->route('variants.index')->with('success', 'Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $color = Color::findOrFail($id);
        $color->delete();
        return redirect()->route('variants.index')->with('success', 'Deleted successfully.');
    }
}
