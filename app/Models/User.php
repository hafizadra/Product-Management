<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Cart;
use App\Models\Order;

class User extends Authenticatable
{
    
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi secara mass-assignment
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
     * User memiliki satu cart 
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * User memiliki banyak order 
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
