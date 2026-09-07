<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawan', function (Blueprint $table) {

            $table->id('id_karyawan');

            $table->string('nama_karyawan', 100);
            $table->string('jabatan', 50);
            $table->string('no_hp', 15);
            $table->string('email', 100)->unique();

            $table->date('tanggal_masuk');

            $table->enum('status', [
                'aktif',
                'nonaktif',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
