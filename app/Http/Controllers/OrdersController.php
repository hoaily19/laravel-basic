<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Orders_item;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public function index()
    {
        $title = 'Quản lý đơn hàng';
        $search = request()->input('search');
        $perPage = request()->input('per_page', 10);
        $sortBy = request()->input('sort_by', 'id');
        $sortOrder = request()->input('sort_order', 'desc');

        $query = Orders::query()->with('orderItems');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        $query->orderBy($sortBy, $sortOrder);

        $orders = $query->paginate($perPage);

        $orders->appends([
            'search' => $search,
            'per_page' => $perPage,
            'sort_by' => $sortBy,
            'sort_order' => $sortOrder,
        ]);

        return view('admin.orders.index', compact('title', 'orders', 'search', 'perPage', 'sortBy', 'sortOrder'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'address_id' => 'required',
            'payment_method' => 'required|in:cod,vnpay,momo',
            'shipping_fee' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Giỏ hàng trống');
        }

        DB::beginTransaction();

        try {
            $shippingFee = $request->input('shipping_fee');
            $totalPrice = $request->input('total_amount');

            $order = Orders::create([
                'user_id' => $user->id,
                'address_id' => $request->address_id,
                'payment_method' => $request->payment_method,
                'total_price' => $totalPrice,
                'shipping_fee' => $shippingFee,
                'discount' => $request->input('discount', 0),
                'status' => 'pending',
            ]);

            foreach ($cartItems as $item) {
                Orders_item::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_variations_id' => $item->product_variations_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->price * $item->quantity,
                ]);
            }

            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            $paymentController = new PaymentController();
            return $paymentController->processPayment($order, $request->payment_method, $totalPrice);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('waring.fail')->with('error', 'Có lỗi khi thêm đơn hàng: ' . $e->getMessage());
        }
    }

    public function create()
    {
        //
    }

    public function show(Orders $orders)
    {
        //
    }

    public function edit(Orders $orders)
    {
        //
    }

    public function update(Request $request, Orders $orders)
    {
        //
    }

    public function destroy($id)
    {
        $order = Orders::find($id);
        Orders_item::where('order_id', $order->id)->delete();
        $order->delete();
        return redirect()->back()->with('success', 'Đơn hàng đã được xoá!');
    }

    // OrderController.php
    public function success()
    {
        return view('waring.success');
    }
}
