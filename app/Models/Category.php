<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'name', 
        'slug', 
        'description', 
        'image',
        'id'
    ];

    public function brands()
    {
        return $this->hasMany(Brands::class, 'categories_id');
    }
}
