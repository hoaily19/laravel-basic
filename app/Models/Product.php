<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    // Các cột cho phép gán giá trị hàng loạt (mass-assignment)
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

    // Định nghĩa quan hệ với bảng categories
    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    
}
