<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok', function (Blueprint $table) {

            $table->id('id_stok');

            $table->foreignId('id_menu')
                ->constrained('menu', 'id_menu')
                ->cascadeOnDelete();

            $table->integer('stok_masuk');
            $table->integer('stok_keluar');
            $table->integer('stok_tersedia');

            $table->dateTime('tanggal_update');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok');
    }
};
