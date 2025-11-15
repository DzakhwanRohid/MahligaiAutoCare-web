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
            // Kolom untuk menyimpan jadwal booking (dari /pemesanan)
            $table->dateTime('booking_date')->nullable()->after('status');

            // Kolom untuk menyimpan path file bukti bayar
            $table->string('payment_proof')->nullable()->after('change');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['booking_date', 'payment_proof']);
        });
    }
};
