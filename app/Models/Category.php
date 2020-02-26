<?php

namespace App\Models;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Category extends model {

    protected $fillable = [
        'name',
        'price',
        'category_id',
        'image',
        'description',
    ];
    public function products()
    {
        return $this->hasMany(Product::class);
    }


}