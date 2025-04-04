<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;


class CouponController extends Controller
{
    /**
     * Display a listing of coupons
     */
    public function index()
    {
        $coupons = Coupon::all();
        return view('admin.coupon.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new coupon
     */
    public function create()
    {
        return view('admin.coupon.create');
    }

    /**
     * Store a newly created coupon in storage
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code|max:20',
            'discount' => 'required|numeric|min:0',
            'type' => 'required|in:percentage,fixed',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after:now',
        ]);

        Coupon::create([
            'code' => $request->code,
            'discount' => $request->discount,
            'type' => $request->type,
            'min_order_amount' => $request->min_order_amount,
            'max_uses' => $request->max_uses,
            'expires_at' => $request->expires_at,
            'used_count' => 0,
            'is_active' => true,
        ]);

        return redirect()->route('admin.coupon.index')->with('success', 'Mã giảm giá đã được thêm thành công!');
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupon.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code,' . $id . ',id|max:20',
            'discount' => 'required|numeric|min:0',
            'type' => 'required|in:percentage,fixed',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $coupon = Coupon::findOrFail($id);
        $coupon->code = $request->code;
        $coupon->discount = $request->discount;
        $coupon->type = $request->type;
        $coupon->min_order_amount = $request->min_order_amount;
        $coupon->max_uses = $request->max_uses;
        $coupon->expires_at = $request->expires_at;
        $coupon->save();

        return redirect()->route('admin.coupon.index')->with('success', 'Mã giảm giá được cập nhật!');
    }

    public function delete($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        return redirect()->route('admin.coupon.index')->with('success', 'Mã giảm giá được xóa!');
    }

    public function applyCoupon(Request $request)
    {
        Log::info('Apply Coupon Request:', $request->all());

        $request->validate([
            'coupon_code' => 'required|string',
            'total_amount' => 'required|numeric'
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->where(function ($query) {
                $query->whereNull('max_uses')
                    ->orWhereRaw('used_count < max_uses');
            })
            ->first();

        if (!$coupon) {
            Log::error('Coupon not found or invalid', ['code' => $request->coupon_code]);
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không hợp lệ hoặc đã hết hạn.'
            ], 404);
        }

        if ($coupon->min_order_amount && $request->total_amount < $coupon->min_order_amount) {
            Log::error('Order amount too low', [
                'order_amount' => $request->total_amount,
                'min_required' => $coupon->min_order_amount
            ]);
            return response()->json([
                'success' => false,
                'message' => sprintf(
                    'Đơn hàng tối thiểu phải đạt %s để áp dụng mã này.',
                    number_format($coupon->min_order_amount) . '₫'
                )
            ], 400);
        }

        // Tính toán giảm giá
        $discount = $coupon->type === 'fixed'
            ? $coupon->discount
            : ($request->total_amount * $coupon->discount / 100);

        $newTotal = $request->total_amount - $discount;

        // Cập nhật số lần sử dụng
        $coupon->increment('used_count');

        Log::info('Coupon applied successfully', [
            'coupon' => $coupon->code,
            'discount' => $discount,
            'new_total' => $newTotal
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã giảm giá thành công!',
            'discount' => $discount,
            'new_total' => $newTotal,
            'coupon_type' => $coupon->type
        ]);
    }
}
