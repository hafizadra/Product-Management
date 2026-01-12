<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'payment_account_name')) {
                $table->string('payment_account_name')->nullable()->after('default_shipping_address');
            }
            if (!Schema::hasColumn('users', 'payment_card_number')) {
                $table->string('payment_card_number', 25)->nullable()->after('payment_account_name');
            }
            if (!Schema::hasColumn('users', 'payment_card_expiry')) {
                $table->string('payment_card_expiry', 10)->nullable()->after('payment_card_number');
            }
            if (!Schema::hasColumn('users', 'payment_card_cvc')) {
                $table->string('payment_card_cvc', 10)->nullable()->after('payment_card_expiry');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $drops = [
                'payment_account_name',
                'payment_card_number',
                'payment_card_expiry',
                'payment_card_cvc',
            ];

            foreach ($drops as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
