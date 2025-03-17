<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variations extends Model
{
    protected $table = 'product_variations';
    protected $fillable = [
        'product_id',
        'size',
        'color',
        'price',
        'image',
        'stock',
        'sku',
    ];
}
