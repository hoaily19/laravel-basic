<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Orders_item extends Model
{
    use HasFactory;

    protected $table = 'orders_item'; // Điều chỉnh tên bảng nếu khác

    protected $fillable = [
        'order_id',
        'product_id',
        'product_variations_id',
        'quantity',
        'price',
        'subtotal',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variation()
    {
        return $this->belongsTo(Variations::class, 'product_variations_id'); // Điều chỉnh tên model nếu khác
    }
}