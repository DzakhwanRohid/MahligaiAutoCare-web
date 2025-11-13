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
        // Slot bisa null (jika masih antre/menunggu)
        // Kita asumsikan ada 4 slot (1-4)
        $table->integer('slot')->nullable()->after('status');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('transactions', function (Blueprint $table) {
        $table->dropColumn('slot');
    });
}
};
