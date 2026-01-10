<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('author')->nullable()->after('name');
            $table->string('publisher')->nullable()->after('author');
            $table->string('isbn')->nullable()->after('publisher');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['author', 'publisher', 'isbn']);
        });
    }
};
