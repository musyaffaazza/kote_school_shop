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
        Schema::table('pesanan', function (Blueprint $table) {
            $table->text('catatan')->nullable()->after('metode_pembayaran');
        });

        Schema::table('detail_pesanan', function (Blueprint $table) {
            $table->string('opsi')->nullable()->after('jumlah');
            $table->text('catatan')->nullable()->after('opsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn('catatan');
        });

        Schema::table('detail_pesanan', function (Blueprint $table) {
            $table->dropColumn(['opsi', 'catatan']);
        });
    }
};
