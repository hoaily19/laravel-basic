<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Orders extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'address_id',
        'status',
        'total_price',
        'payment_method',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function orderItems()
    {
        return $this->hasMany(Orders_item::class, 'order_id', 'id');
    }

    public function getDisplayStatusAttribute()
    {
        $statusMap = [
            'pending' => 'Chờ Xử Lý',
            'paid' => 'Đã Thanh Toán',
            'shipping' => 'Đang Vận Chuyển',
            'delivering' => 'Đang Giao Hàng',
            'delivered' => 'Đã Giao Hàng',
            'completed' => 'Hoàn Thành',
            'cancelled' => 'Đã Hủy',
        ];

        return $statusMap[$this->status] ?? $this->status;
    }

    public function calculateTotalOriginalPrice()
    {
        $totalOriginalPrice = 0;
        foreach ($this->orderItems as $item) {
            $originalPrice = $item->variation->original_price ?? $item->product->original_price ?? 0;
            $totalOriginalPrice += $originalPrice * $item->quantity;
        }
        return $totalOriginalPrice;
    }

    public function calculateTotalProfit()
    {
        $totalPrice = $this->total_price ?? 0; 
        $totalOriginalPrice = $this->calculateTotalOriginalPrice(); 
        $shippingFee = 20000; 

        return $totalPrice - $totalOriginalPrice - $shippingFee;
    }
}