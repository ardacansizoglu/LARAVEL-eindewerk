<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'available_sizes',
        'price',
        'brand_id',
        'description',
        'image'
    ];

    protected $casts = [
        'available_sizes' => 'array',
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function usersInCart()
    {
        return $this->belongsToMany(User::class, 'shopping_cart')
            ->withPivot('quantity', 'size')
            ->withTimestamps();
    }
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product')

            ->withPivot('quantity', 'size')
            ->withTimestamps();
    }
}
