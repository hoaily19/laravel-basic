<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Orders_item;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Variations;
use App\Models\Address;
use App\Mail\OrderStatus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class OrdersController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:cod,vnpay,momo',
            'total_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('product', 'variation')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Giỏ hàng trống');
        }

        DB::beginTransaction();

        try {
            $subtotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);

            $address = Address::findOrFail($request->address_id);

            $shippingFee = $this->calculateShippingFee($address, $cartItems);

            $isFreeShip = $this->checkFreeShip($subtotal, $address);
            $finalShippingFee = $isFreeShip ? 0 : $shippingFee;

            $discount = $request->input('discount', 0);
            $totalPrice = $subtotal + $finalShippingFee - $discount;

            $order = Orders::create([
                'user_id' => $user->id,
                'address_id' => $request->address_id,
                'payment_method' => $request->payment_method,
                'total_price' => $totalPrice,
                'shipping_fee' => $finalShippingFee,
                'discount' => $discount,
                'status' => 'pending',
            ]);

            foreach ($cartItems as $item) {
                $orderItem = Orders_item::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_variations_id' => $item->product_variations_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->price * $item->quantity,
                ]);

                if ($item->product_variations_id) {
                    $variation = Variations::findOrFail($item->product_variations_id);
                    if ($variation->stock < $item->quantity) {
                        throw new \Exception("Số lượng tồn kho của biến thể {$variation->id} không đủ.");
                    }
                    $variation->stock -= $item->quantity;
                    $variation->save();
                } else {
                    $product = Product::findOrFail($item->product_id);
                    if ($product->stock < $item->quantity) {
                        throw new \Exception("Số lượng tồn kho của sản phẩm {$product->id} không đủ.");
                    }
                    $product->stock -= $item->quantity;
                    $product->save();
                }
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


    public function calculateShipping(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
        ]);

        $address = Address::findOrFail($request->address_id);
        $cartItems = Cart::where('user_id', Auth::id())->with('product', 'variation')->get();

        $shippingFee = $this->calculateShippingFee($address, $cartItems);
        $subtotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);
        $isFreeShip = $this->checkFreeShip($subtotal, $address);
        $finalShippingFee = $isFreeShip ? 0 : $shippingFee;

        return response()->json([
            'success' => true,
            'shipping_fee' => $finalShippingFee,
            'is_free_ship' => $isFreeShip,
        ]);
    }

    private function calculateShippingFee(Address $address, $cartItems)
    {
        try {
            $client = new Client();
            $response = $client->post('https://online-gateway.ghn.vn/shiip/public-api/v2/shipping-order/fee', [
                'headers' => [
                    'Token' => env('GHN_API_TOKEN'),
                    'Content-Type' => 'application/json',
                    'ShopId' => env('GHN_SHOP_ID'),
                ],
                'json' => [
                    'from_district_id' => env('GHN_SHOP_DISTRICT_ID'),
                    'from_ward_code' => env('GHN_SHOP_WARD_CODE'),
                    'service_id' => 53320,
                    'service_type_id' => 2,
                    'to_district_id' => $this->getDistrictId($address->district),
                    'to_ward_code' => $this->getWardCode($address->ward),
                    'height' => 10,
                    'length' => 20,
                    'weight' => $this->calculateTotalWeight($cartItems),
                    'width' => 15,
                    'insurance_value' => 0,
                    'coupon' => null,
                ],
            ]);

            $result = json_decode($response->getBody(), true);
            if ($result['code'] === 200) {
                return $result['data']['total'];
            } else {
                throw new \Exception('Không thể tính phí vận chuyển: ' . ($result['message'] ?? 'Lỗi không xác định'));
            }
        } catch (\Exception $e) {
            Log::error('Lỗi tính phí vận chuyển: ' . $e->getMessage());
            return 20000; 
        }
    }

    private function checkFreeShip($subtotal, Address $address)
    {
        $minimumOrderValue = 500000;
        $freeShipProvince = 'Đăk Lăk';
        return $subtotal >= $minimumOrderValue || $address->province === $freeShipProvince;
    }

    private function getDistrictId($districtName)
    {
        try {
            $client = new Client();
            $response = $client->post('https://online-gateway.ghn.vn/shiip/public-api/master-data/district', [
                'headers' => [
                    'Token' => env('GHN_API_TOKEN'),
                ],
            ]);
            $districts = json_decode($response->getBody(), true)['data'];

            foreach ($districts as $district) {
                if (
                    str_contains(strtolower($district['DistrictName']), strtolower($districtName)) ||
                    str_contains(strtolower($districtName), strtolower($district['DistrictName']))
                ) {
                    return $district['DistrictID'];
                }
            }
            throw new \Exception('Không tìm thấy quận/huyện: ' . $districtName);
        } catch (\Exception $e) {
            Log::error('Lỗi lấy mã quận/huyện: ' . $e->getMessage());
            return env('GHN_SHOP_DISTRICT_ID');
        }
    }

    private function getWardCode($wardName)
    {
        try {
            $client = new Client();
            $response = $client->post('https://online-gateway.ghn.vn/shiip/public-api/master-data/ward', [
                'headers' => [
                    'Token' => env('GHN_API_TOKEN'),
                ],
                'json' => [
                    'district_id' => $this->getDistrictId($wardName),
                ],
            ]);
            $wards = json_decode($response->getBody(), true)['data'];

            foreach ($wards as $ward) {
                if (
                    str_contains(strtolower($ward['WardName']), strtolower($wardName)) ||
                    str_contains(strtolower($wardName), strtolower($ward['WardName']))
                ) {
                    return (string) $ward['WardCode'];
                }
            }
            throw new \Exception('Không tìm thấy xã/phường: ' . $wardName);
        } catch (\Exception $e) {
            Log::error('Lỗi lấy mã xã/phường: ' . $e->getMessage());
            return env('GHN_SHOP_WARD_CODE');
        }
    }

    private function calculateTotalWeight($cartItems)
    {
        $totalWeight = 0;
        foreach ($cartItems as $item) {
            $weight = $item->product->weight ?? 1000;
            $totalWeight += $weight * $item->quantity;
        }
        return max(100, $totalWeight);
    }


    public function profileOrders(Request $request)
    {
        $user = Auth::user();
        $status = $request->query('status', 'all');

        $query = Orders::where('user_id', $user->id)
            ->with('orderItems.product', 'orderItems.variation.color', 'orderItems.variation.size');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('profile.orders', compact('orders'));
    }

    public function index()
    {
        $title = 'Quản lý đơn hàng';
        $search = request()->input('search');
        $perPage = request()->input('per_page', 10);
        $sortBy = request()->input('sort_by', 'id');
        $sortOrder = request()->input('sort_order', 'desc');

        $query = Orders::query()->with('orderItems.product', 'orderItems.variation.color', 'orderItems.variation.size', 'user', 'address');
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

        return view('admin.order.index', compact('title', 'orders', 'search', 'perPage', 'sortBy', 'sortOrder'));
    }

    public function create()
    {
        //
    }

    public function show($id)
    {
        $order = Orders::with('orderItems')->findOrFail($id);
        return view('admin.order.show', compact('order'));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivering,cancelled,completed',
        ]);

        $order = Orders::findOrFail($id);
        $order->update(['status' => $request->status]);

        if ($order->user && $order->user->email) {
            Mail::to($order->user->email)->send(new OrderStatus($order));
        }

        return redirect()->route('admin.order.index')->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    public function destroy($id)
    {
        $order = Orders::find($id);
        Orders_item::where('order_id', $order->id)->delete();
        $order->delete();
        return redirect()->back()->with('success', 'Đơn hàng đã được xoá!');
    }

    public function success()
    {
        return view('waring.success');
    }
}
