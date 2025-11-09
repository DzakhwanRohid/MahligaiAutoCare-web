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
            // Kita tambahkan kolom baru setelah kolom 'status'
            $table->string('payment_method')->nullable()->after('status');
            $table->integer('amount_paid')->nullable()->after('payment_method');
            $table->integer('change')->nullable()->after('amount_paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'amount_paid', 'change']);
        });
    }
};
