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
        Schema::create('carts', function (Blueprint $table) {
    $table->id();

    // cart milik user yang sedang login
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();

    $table->timestamps();

    // 1 user = 1 cart (menghindari cart ganda untuk user yang sama)
    $table->unique('user_id');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
