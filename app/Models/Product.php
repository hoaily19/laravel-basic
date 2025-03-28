<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    protected $fillable = [
        'name', 
        'slug', 
        'categories_id', 
        'brand_id',
        'description', 
        'price',  
        'stock', 
        'sku', 
        'image', 
        'images', 
        'is_active', 
        'view_count'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    
}
