<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'shipping_address',
        'payment_method',
        'subtotal',
        'total',
        'status',
    ];

    /**
     * Order dimiliki oleh user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Order punya banyak item
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
