<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = ['code', 'discount', 'type', 'min_order_amount', 'max_uses','start_date', 'used_count', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
        'start_date' => 'datetime',
    ];
}