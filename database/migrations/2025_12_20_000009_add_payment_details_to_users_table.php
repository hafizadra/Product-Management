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
        Schema::table('users', function (Blueprint $table) {
            $table->string('payment_account_name')->nullable()->after('default_shipping_address');
            $table->string('payment_card_number', 25)->nullable()->after('payment_account_name');
            $table->string('payment_card_expiry', 10)->nullable()->after('payment_card_number');
            $table->string('payment_card_cvc', 10)->nullable()->after('payment_card_expiry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'payment_account_name',
                'payment_card_number',
                'payment_card_expiry',
                'payment_card_cvc',
            ]);
        });
    }
};
