<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <- penting
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory; // <- ini meng-aktifkan Product::factory()

    // kolom yang boleh diisi mass-assignment
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
    ];

    // relasi: product belongsTo category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // custom attribute: price_formatted
    protected $appends = ['price_formatted'];

    public function getPriceFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
