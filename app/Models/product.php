<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends model
{
    protected $fillable = [
        'name',
        'price',
        'category_id',
        'image',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);

    }

    public function getPriceAttribute($price){
        return priceAfterCurrencyChange($price);//rename
    }
    //accessors mutators
}