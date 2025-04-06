<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Variations;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('is_login');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Giỏ hàng';
        $user = Auth::user();
        $carts = Cart::where('user_id', $user->id)
            ->with('product', 'variation.size', 'variation.color')
            ->get();
    
        $total = $carts->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    
        $products = \App\Models\Product::paginate(5);
        $categories = \App\Models\Category::all();
        $brands = \App\Models\Brands::whereIn('categories_id', $categories->pluck('id'))->get();
    
        $cartItemCount = 0;
        if (Auth::check()) {
            $cartItemCount = Cart::where('user_id', Auth::id())->sum('quantity');
        }
    
        // Pass all necessary variables to the view, including $carts and $total
        return view('cart', compact('carts', 'total', 'products', 'categories', 'brands', 'cartItemCount', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variations_id' => 'nullable|exists:product_variations,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $productId = $request->input('product_id');
        $variationId = $request->input('product_variations_id');
        $quantity = $request->input('quantity');

        $product = Product::findOrFail($productId);
        $variation = $variationId ? Variations::find($variationId) : null;

        $price = $variation ? $variation->price : $product->price;
        $stock = $variation ? $variation->stock : $product->stock;

        if ($quantity > $stock) {
            return redirect()->back()->with('error', 'Số lượng yêu cầu vượt quá số lượng tồn kho!');
        }

        $cartItem = Cart::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->where('product_variations_id', $variationId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            if ($cartItem->quantity > $stock) {
                return redirect()->back()->with('error', 'Tổng số lượng trong giỏ hàng vượt quá số lượng tồn kho!');
            }
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_id' => $productId,
                'product_variations_id' => $variationId,
                'quantity' => $quantity,
                'price' => $price,
            ]);
        }

        return redirect()->back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng!');
    }

    /**
     * Update the quantity of a cart item (for + and - buttons).
     */
    public function update(Request $request, $id, $change)
    {
        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $newQuantity = $cart->quantity + $change;

        if ($newQuantity < 1) {
            return redirect()->route('cart.index')->with('error', 'Số lượng không thể nhỏ hơn 1!');
        }

        $stock = $cart->variation ? $cart->variation->stock : $cart->product->stock;
        if ($newQuantity > $stock) {
            return redirect()->route('cart.index')->with('error', 'Số lượng vượt quá tồn kho! Số lượng tối đa là ' . $stock . '.');
        }

        $cart->quantity = $newQuantity;
        $cart->save();

        return redirect()->route('cart.index')->with('success', 'Cập nhật số lượng thành công!');
    }

    /**
     * Update the quantity of a cart item (for direct input).
     */
    public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $newQuantity = $request->input('quantity');

        $stock = $cart->variation ? $cart->variation->stock : $cart->product->stock;
        if ($newQuantity > $stock) {
            return redirect()->route('cart.index')->with('error', 'Số lượng vượt quá tồn kho! Số lượng tối đa là ' . $stock . '.');
        }

        $cart->quantity = $newQuantity;
        $cart->save();

        return redirect()->route('cart.index')->with('success', 'Cập nhật số lượng thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $cart = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cart->delete();

        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng!');
    }

    /**
     * Delete multiple selected items from the cart.
     */
    public function deleteSelected(Request $request)
    {
        $selectedItems = $request->input('selected_items', []);

        if (empty($selectedItems)) {
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn ít nhất một sản phẩm để xóa!');
        }

        Cart::whereIn('id', $selectedItems)
            ->where('user_id', Auth::id())
            ->delete();

        return redirect()->route('cart.index')->with('success', 'Đã xóa các sản phẩm được chọn!');
    }

    /**
     * Handle checkout (placeholder for now).
     */
    public function checkout()
    {
        $user = Auth::user();

        $selectedItems = request()->input('selected_items', []);

        if (empty($selectedItems)) {
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn ít nhất một sản phẩm để thanh toán!');
        }

        $carts = Cart::where('user_id', $user->id)
            ->whereIn('id', $selectedItems)
            ->with('product', 'variation')
            ->get();

        $totalAmount = $carts->sum(function ($item) {
            return $item->price * $item->quantity;
        });
        $addresses = Address::where('user_id', $user->id)->get();

        return view('checkout', compact('carts', 'totalAmount', 'addresses'));
    }
}
