<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
    $table->id();

    $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();

    // jumlah beli
    $table->unsignedInteger('qty')->default(1);

    // simpan harga saat item masuk cart (biar konsisten kalau harga produk berubah)
    $table->unsignedBigInteger('price');

    $table->timestamps();

    // 1 produk hanya boleh 1 baris dalam cart yang sama (qty-nya yang berubah)
    $table->unique(['cart_id', 'product_id']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
