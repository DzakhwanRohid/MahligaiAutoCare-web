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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Misal: "Diskon Kemerdekaan"
            $table->string('code')->unique(); // Misal: "CUCIHEMAT10"
            $table->enum('type', ['percentage', 'fixed']); // Tipe diskon: persen atau nominal
            $table->decimal('value', 10, 2); // Nilai diskonnya (misal: 20 untuk 20% atau 10000 untuk Rp10.000)
            $table->date('start_date'); // Tanggal mulai berlaku
            $table->date('end_date'); // Tanggal berakhir
            $table->boolean('is_active')->default(true); // Status aktif/tidak aktif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
