<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UlasanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ulasan')->insert([
            [
                'id_user' => 3,
                'id_menu' => 1,
                'rating' => 5,
                'komentar' => 'Aren Latte sangat enak dan creamy.',
                'tanggal_ulasan' => '2026-08-01 09:00:00',
                'status' => 'aktif',
            ],
            [
                'id_user' => 4,
                'id_menu' => 2,
                'rating' => 4,
                'komentar' => 'Americano mantap, tidak terlalu pahit.',
                'tanggal_ulasan' => '2026-08-01 10:15:00',
                'status' => 'aktif',
            ],
            [
                'id_user' => 5,
                'id_menu' => 3,
                'rating' => 5,
                'komentar' => 'Coffee Latte favorit saya.',
                'tanggal_ulasan' => '2026-08-02 11:30:00',
                'status' => 'aktif',
            ],
            [
                'id_user' => 6,
                'id_menu' => 4,
                'rating' => 4,
                'komentar' => 'Matcha Latte enak dan tidak terlalu manis.',
                'tanggal_ulasan' => '2026-08-02 13:20:00',
                'status' => 'aktif',
            ],
            [
                'id_user' => 1,
                'id_menu' => 1,
                'rating' => 5,
                'komentar' => 'Pelayanannya cepat dan kopinya nikmat.',
                'tanggal_ulasan' => '2026-08-03 09:45:00',
                'status' => 'aktif',
            ],
            [
                'id_user' => 2,
                'id_menu' => 2,
                'rating' => 3,
                'komentar' => 'Rasa cukup enak, semoga lebih konsisten.',
                'tanggal_ulasan' => '2026-08-03 14:10:00',
                'status' => 'aktif',
            ],
            [
                'id_user' => 3,
                'id_menu' => 3,
                'rating' => 4,
                'komentar' => 'Coffee Latte lembut dan harum.',
                'tanggal_ulasan' => '2026-08-04 08:30:00',
                'status' => 'aktif',
            ],
            [
                'id_user' => 4,
                'id_menu' => 4,
                'rating' => 5,
                'komentar' => 'Matcha Latte recommended!',
                'tanggal_ulasan' => '2026-08-04 15:00:00',
                'status' => 'aktif',
            ],
            [
                'id_user' => 3,
                'id_menu' => 2,
                'rating' => 4,
                'komentar' => 'Harga terjangkau dan rasanya enak.',
                'tanggal_ulasan' => '2026-08-05 10:20:00',
                'status' => 'aktif',
            ],
            [
                'id_user' => 5,
                'id_menu' => 1,
                'rating' => 2,
                'komentar' => 'Pesanan datang agak lama.',
                'tanggal_ulasan' => '2026-08-05 16:40:00',
                'status' => 'nonaktif',
            ],
        ]);
    }
}
