<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
    ];

    /**
     * Cart dimiliki oleh satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Cart memiliki banyak item
     */
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
}
