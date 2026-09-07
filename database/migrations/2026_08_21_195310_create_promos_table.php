<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('nama_promo', 150);
            $table->text('deskripsi')->nullable();
            $table->string('jenis_promo'); // diskon, paket, voucher
            $table->integer('nilai_promo')->default(0);
            $table->string('satuan_nilai')->default('rupiah'); // rupiah, persen
            $table->string('kode_voucher', 50)->nullable()->unique();
            $table->date('periode_mulai')->nullable();
            $table->date('periode_selesai')->nullable();
            $table->string('gambar')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
