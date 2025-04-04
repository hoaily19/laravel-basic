<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['code', 'discount', 'type', 'min_order_amount', 'max_uses', 'used_count', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}