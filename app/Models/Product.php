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
        'original_price',
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

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brands::class, 'brand_id');
    }
    public function variations()
    {
        return $this->hasMany(Variations::class, 'product_id');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites', 'product_id', 'user_id')->withTimestamps();
    }

}
