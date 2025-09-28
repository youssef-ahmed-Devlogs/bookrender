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
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('transaction_id')->nullable()->after('price');
            $table->string('status')->default('pending')->after('transaction_id');
            $table->string('payment_method')->nullable()->after('status');
            $table->string('currency_code')->default('USD')->after('payment_method');
            $table->string('error_code')->nullable()->after('currency_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'transaction_id',
                'status',
                'payment_method',
                'currency_code',
                'error_code'
            ]);
        });
    }
};
