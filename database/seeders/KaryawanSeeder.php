<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('karyawan')->insert([
            [
                'nama_karyawan' => 'Fio',
                'jabatan' => 'Barista',
                'no_hp' => '0812-3456-7890',
                'email' => 'fio@gmail.com',
                'tanggal_masuk' => '2025-01-10',
                'status' => 'aktif',
            ],
            [
                'nama_karyawan' => 'Azza',
                'jabatan' => 'Kasir',
                'no_hp' => '0821-1377-8035',
                'email' => 'azza@gmail.com',
                'tanggal_masuk' => '2025-02-15',
                'status' => 'aktif',
            ],
        ]);
    }
}
