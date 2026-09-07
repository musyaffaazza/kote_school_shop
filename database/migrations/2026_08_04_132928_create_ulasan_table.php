<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulasan', function (Blueprint $table) {

            $table->id('id_ulasan');

            $table->foreignId('id_user')
                ->constrained('users', 'id_user')
                ->cascadeOnDelete();

            $table->foreignId('id_menu')
                ->constrained('menu', 'id_menu')
                ->cascadeOnDelete();

            $table->tinyInteger('rating');
            $table->text('komentar');
            $table->dateTime('tanggal_ulasan');

            $table->enum('status', [
                'aktif',
                'nonaktif',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};
