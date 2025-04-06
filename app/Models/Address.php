<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'receiver_name',
        'phone',
        'province',
        'district',
        'ward',
        'street',
        'is_default',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getAddressAttribute()
    {
        return implode(', ', array_filter([
            $this->street,
            $this->ward,
            $this->district,
            $this->province,
        ]));
    }
}