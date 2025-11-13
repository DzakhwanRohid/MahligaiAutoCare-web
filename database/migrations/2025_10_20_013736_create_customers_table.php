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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();

            // === KOLOM BARU YANG WAJIB DITAMBAHKAN ===
            $table->string('license_plate')->unique()->nullable(); // No. Polisi
            $table->string('vehicle_type')->nullable();            // Tipe Kendaraan

            // Relasi ke tabel users (nullable karena walk-in tidak punya akun)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
