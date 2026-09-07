<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('id_pesanan');

            $table->foreignId('id_user')
                ->constrained('users', 'id_user')
                ->cascadeOnDelete();

            $table->dateTime('tanggal_pesan');
            $table->decimal('total_harga', 12, 2);

            $table->string('metode_pembayaran', 50);

            $table->enum('status_pesanan', [
                'diproses',
                'selesai',
                'dibatalkan',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
