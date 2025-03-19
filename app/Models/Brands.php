<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brands extends Model
{
    protected $table = 'brand';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'slug',
        'description',
        'categories_id',
        'image',
    ];


    public function categories()
    {
        return $this->belongsTo(Category::class, 'categories_id', 'id');
    }
}
