<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
        'images',
        'has_image',
        'has_video'
    ];

    protected $casts = [
        'images' => 'array',
        'has_image' => 'boolean',
        'has_video' => 'boolean',
    ];
    public function likes()
    {
        return $this->belongsToMany(User::class, 'review_likes', 'review_id', 'user_id')
            ->withTimestamps();
    }

    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
