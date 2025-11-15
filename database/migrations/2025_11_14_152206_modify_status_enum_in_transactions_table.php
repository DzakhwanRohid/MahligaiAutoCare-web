<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah enum untuk menambah status baru
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('status', [
                'Menunggu',
                'Terkonfirmasi', // <-- BARU
                'Sedang Dicuci',
                'Selesai',
                'Sudah Dibayar',
                'Ditolak'        // <-- BARU
            ])->default('Menunggu')->change();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Hati-hati saat rollback, data bisa hilang
            $table->enum('status', [
                'Menunggu',
                'Sedang Dicuci',
                'Selesai',
                'Sudah Dibayar'
            ])->default('Menunggu')->change();
        });
    }
};
