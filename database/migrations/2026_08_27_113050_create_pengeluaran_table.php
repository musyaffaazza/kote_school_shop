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
        Schema::create('pengeluaran', function (Blueprint $table) {
            $table->id('id_pengeluaran');
            $table->string('kategori'); // Bahan Baku, Operasional, Peralatan, Listrik & Internet, Lainnya
            $table->string('keterangan');
            $table->decimal('jumlah', 12, 2);
            $table->date('tanggal');
            $table->string('metode_pembayaran')->nullable()->default('Tunai');
            $table->string('bukti_transaksi')->nullable();
            $table->foreignId('id_user')->nullable()->constrained('users', 'id_user')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengeluaran');
    }
};
