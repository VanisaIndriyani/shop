<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 
        'slug', 
        'description', 
        'price', 
        'sale_price', 
        'image', 
        'images',
        'category', 
        'product_type',
        'sizes',
        'stock'
    ];

    protected $casts = [
        'images' => 'array',
        'sizes' => 'array',
    ];
}
