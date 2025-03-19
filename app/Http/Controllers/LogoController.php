<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Logo;

class LogoController extends Controller
{
    public function index()
    {
        $logos = Logo::all();
        return view('admin.logo.index', compact('logos'));
    }

    public function create()
    {
        return view('admin.logo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $request->file('image')->store('logos', 'public');

        Logo::create([
            'name' => $request->name,
            'image' => $imagePath,
            'is_active' => false, // Default to inactive; user can toggle later
        ]);

        return redirect()->route('admin.logo.index')->with('success', 'Logo đã được tạo thành công.');
    }

    public function setActive($id)
    {
        // Optional: Deactivate all logos before activating the selected one (if you want only one active)
        // Logo::where('is_active', true)->update(['is_active' => false]);

        // Activate the selected logo
        $logo = Logo::findOrFail($id);
        $logo->update(['is_active' => true]);

        return redirect()->route('admin.logo.index')->with('success', 'Logo đã được chọn làm logo hiển thị.');
    }

    public function toggleActive($id)
    {
        $logo = Logo::findOrFail($id);
        $logo->update(['is_active' => !$logo->is_active]);

        $status = $logo->is_active ? 'hiển thị' : 'không hiển thị';
        return redirect()->route('admin.logo.index')->with('success', "Logo đã được thay đổi trạng thái thành $status.");
    }

    public function delete($id)
    {
        $logo = Logo::findOrFail($id);
        $logo->delete();

        return redirect()->route('admin.logo.index')->with('success', 'Logo đã được xóa thành công.');
    }
}