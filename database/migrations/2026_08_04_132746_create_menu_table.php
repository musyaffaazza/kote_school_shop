<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id('id_menu');
            $table->string('nama_menu', 100);
            $table->string('kategori', 50);
            $table->decimal('harga', 12, 2);
            $table->integer('stok');
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->enum('status', ['tersedia', 'habis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
