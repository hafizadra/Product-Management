<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <- penting
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory; // Category::factory() bisa dipakai kalau perlu

    protected $fillable = [
        'name',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
