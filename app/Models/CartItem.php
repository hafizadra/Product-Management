<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'qty',
        'price',
    ];

    /**
     * item milik satu cart
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Item mengacu ke satu produk
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
