<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::all();
        return view('admin.coupon.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupon.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code|max:20',
            'discount' => 'required|numeric|min:0',
            'type' => 'required|in:percentage,fixed',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
        ], [
            'code.required' => 'Vui lòng nhập mã giảm giá.',
            'code.string' => 'Mã giảm giá phải là chuỗi ký tự.',
            'code.unique' => 'Mã giảm giá đã tồn tại.',
            'code.max' => 'Mã giảm giá không được dài quá 20 ký tự.',
            'discount.required' => 'Vui lòng nhập giá trị giảm giá.',
            'discount.numeric' => 'Giá trị giảm giá phải là số.',
            'discount.min' => 'Giá trị giảm giá không được nhỏ hơn 0.',
            'type.required' => 'Vui lòng chọn loại giảm giá.',
            'type.in' => 'Loại giảm giá phải là phần trăm hoặc số tiền cố định.',
            'min_order_amount.numeric' => 'Đơn hàng tối thiểu phải là số.',
            'min_order_amount.min' => 'Đơn hàng tối thiểu không được nhỏ hơn 0.',
            'max_uses.integer' => 'Số lần sử dụng tối đa phải là số nguyên.',
            'max_uses.min' => 'Số lần sử dụng tối đa phải ít nhất là 1.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'expires_at.date' => 'Ngày hết hạn không hợp lệ.',
            'expires_at.after_or_equal' => 'Ngày hết hạn phải sau hoặc bằng ngày bắt đầu.',
            'is_active.boolean' => 'Trạng thái kích hoạt không hợp lệ.',
        ]);

        Coupon::create([
            'code' => $request->code,
            'discount' => $request->discount,
            'type' => $request->type,
            'min_order_amount' => $request->min_order_amount,
            'max_uses' => $request->max_uses,
            'start_date' => $request->start_date,
            'expires_at' => $request->expires_at,
            'used_count' => 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.coupon.index')->with('success', 'Mã giảm giá đã được thêm thành công!');
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        Log::info('Edit Coupon', ['id' => $id, 'coupon' => $coupon->toArray()]);
        return view('admin.coupon.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        Log::info('Updating Coupon', ['id' => $id, 'request' => $request->all()]);

        $request->validate([
            'code' => 'required|string|unique:coupons,code,' . $id . ',id|max:20',
            'discount' => 'required|numeric|min:0',
            'type' => 'required|in:percentage,fixed',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
        ], [
            'code.required' => 'Vui lòng nhập mã giảm giá.',
            'code.string' => 'Mã giảm giá phải là chuỗi ký tự.',
            'code.unique' => 'Mã giảm giá đã tồn tại.',
            'code.max' => 'Mã giảm giá không được dài quá 20 ký tự.',
            'discount.required' => 'Vui lòng nhập giá trị giảm giá.',
            'discount.numeric' => 'Giá trị giảm giá phải là số.',
            'discount.min' => 'Giá trị giảm giá không được nhỏ hơn 0.',
            'type.required' => 'Vui lòng chọn loại giảm giá.',
            'type.in' => 'Loại giảm giá phải là phần trăm hoặc số tiền cố định.',
            'min_order_amount.numeric' => 'Đơn hàng tối thiểu phải là số.',
            'min_order_amount.min' => 'Đơn hàng tối thiểu không được nhỏ hơn 0.',
            'max_uses.integer' => 'Số lần sử dụng tối đa phải là số nguyên.',
            'max_uses.min' => 'Số lần sử dụng tối đa phải ít nhất là 1.',
            'start_date.date' => 'Ngày bắt đầu không hợp lệ.',
            'expires_at.date' => 'Ngày hết hạn không hợp lệ.',
            'expires_at.after_or_equal' => 'Ngày hết hạn phải sau hoặc bằng ngày bắt đầu.',
            'is_active.boolean' => 'Trạng thái kích hoạt không hợp lệ.',
        ]);

        $coupon = Coupon::findOrFail($id);
        $result = $coupon->update([
            'code' => $request->code,
            'discount' => $request->discount,
            'type' => $request->type,
            'min_order_amount' => $request->min_order_amount,
            'max_uses' => $request->max_uses,
            'start_date' => $request->start_date,
            'expires_at' => $request->expires_at,
            'is_active' => $request->has('is_active'),
        ]);

        Log::info('Coupon Update Result', [
            'id' => $id,
            'result' => $result,
            'coupon' => $coupon->fresh()->toArray()
        ]);

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
                $query->whereNull('start_date')
                      ->orWhere('start_date', '<=', now());
            })
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
                'message' => 'Mã giảm giá không hợp lệ, chưa bắt đầu hoặc đã hết hạn.'
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

        $discount = $coupon->type === 'fixed'
            ? $coupon->discount
            : ($request->total_amount * $coupon->discount / 100);

        $newTotal = $request->total_amount - $discount;

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