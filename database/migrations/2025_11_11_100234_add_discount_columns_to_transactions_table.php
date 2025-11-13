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
            // 1. Tambah kolom untuk harga dasar
            $table->decimal('base_price', 10, 2)->after('payment_method')->nullable();

            // 2. Tambah kolom untuk jumlah diskon
            $table->decimal('discount', 10, 2)->default(0)->after('base_price');

            // 3. Tambah foreign key untuk promotion_id
            // onDelete('set null') artinya jika promosi dihapus, riwayat transaksi tidak ikut terhapus
            $table->foreignId('promotion_id')->nullable()->after('service_id')
                  ->constrained('promotions')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['promotion_id']);
            $table->dropColumn(['promotion_id', 'base_price', 'discount']);
        });
    }
};
