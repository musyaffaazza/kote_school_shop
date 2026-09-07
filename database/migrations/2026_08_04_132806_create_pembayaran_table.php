<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {

            $table->id('id_pembayaran');

            $table->foreignId('id_pesanan')
                ->constrained('pesanan', 'id_pesanan')
                ->cascadeOnDelete();

            $table->string('metode', 50);
            $table->decimal('nominal', 12, 2);
            $table->dateTime('tanggal_bayar');

            $table->enum('status', [
                'berhasil',
                'menunggu',
                'gagal',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
