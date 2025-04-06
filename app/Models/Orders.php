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
    public function variation()
    {
        return $this->belongsTo(Variations::class, 'product_variations_id');
    }

    public function getDisplayStatusAttribute()
    {
        $statusMap = [
            'pending' => 'Chờ Xử Lý',
            'paid' => 'Đã Thanh Toán',
            'shipping' => 'Đang Vận Chuyển',
            'delivering' => 'Đang Giao Hàng',
            'cancelled' => 'Đã Hủy',
        ];

        return $statusMap[$this->status] ?? $this->status;
    }
}
