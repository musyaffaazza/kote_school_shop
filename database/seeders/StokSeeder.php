<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('stok')->insert([

            [
                'id_menu' => 1,
                'stok_masuk' => 25,
                'stok_keluar' => 3,
                'stok_tersedia' => 22,
                'tanggal_update' => '2026-08-05 16:00:00',
            ],

            [
                'id_menu' => 2,
                'stok_masuk' => 25,
                'stok_keluar' => 5,
                'stok_tersedia' => 20,
                'tanggal_update' => '2026-08-05 16:00:00',
            ],

            [
                'id_menu' => 3,
                'stok_masuk' => 25,
                'stok_keluar' => 4,
                'stok_tersedia' => 21,
                'tanggal_update' => '2026-08-05 16:00:00',
            ],

            [
                'id_menu' => 4,
                'stok_masuk' => 25,
                'stok_keluar' => 4,
                'stok_tersedia' => 21,
                'tanggal_update' => '2026-08-05 16:00:00',
            ],

        ]);
    }
}
