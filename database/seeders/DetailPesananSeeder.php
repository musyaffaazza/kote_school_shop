<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DetailPesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Detail pesanan konsisten dengan total_harga di PesananSeeder:
     * Pesanan 1: Aren Latte x1 = 8000
     * Pesanan 2: Americano x2 = 14000
     * Pesanan 3: Matcha Latte x1 + Coffee Latte x1 + Americano x1 = 10000+10000+4000? No => recalc
     * Recalc: harga menu: Aren=8000, Americano=7000, Matcha=10000, Coffee Latte=10000
     * Pesanan 3 total 24000: Matcha x1(10000) + Coffee Latte x1(10000) + Americano x1(4000)? No.
     *   => Aren x3 = 24000
     * Pesanan 4 total 30000: Coffee Latte x3 = 30000
     * Pesanan 5 total 7000: Americano x1 = 7000
     * Pesanan 6 total 16000: Aren x2 = 16000
     * Pesanan 7 total 10000: Matcha Latte x1 = 10000
     * Pesanan 8 total 30000: Matcha x1(10000) + Coffee Latte x1(10000) + Aren x1(8000) + ... hmm
     *   => Coffee Latte x3 = 30000
     * Pesanan 9 total 16000: Aren x2 = 16000
     * Pesanan 10 total 7000: Americano x1 = 7000
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('detail_pesanan')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('detail_pesanan')->insert([
            // Pesanan 1: Aren Latte x1 = 8000
            [
                'id_pesanan' => 1,
                'id_menu' => 1,
                'jumlah' => 1,
                'opsi' => 'Ice',
                'catatan' => null,
                'harga' => 8000,
                'subtotal' => 8000,
            ],

            // Pesanan 2: Americano x2 = 14000
            [
                'id_pesanan' => 2,
                'id_menu' => 2,
                'jumlah' => 2,
                'opsi' => 'Ice',
                'catatan' => null,
                'harga' => 7000,
                'subtotal' => 14000,
            ],

            // Pesanan 3: Aren Latte x3 = 24000
            [
                'id_pesanan' => 3,
                'id_menu' => 1,
                'jumlah' => 3,
                'opsi' => 'Hot',
                'catatan' => 'Jangan terlalu manis',
                'harga' => 8000,
                'subtotal' => 24000,
            ],

            // Pesanan 4: Coffee Latte x3 = 30000
            [
                'id_pesanan' => 4,
                'id_menu' => 4,
                'jumlah' => 3,
                'opsi' => 'Ice',
                'catatan' => null,
                'harga' => 10000,
                'subtotal' => 30000,
            ],

            // Pesanan 5: Americano x1 = 7000
            [
                'id_pesanan' => 5,
                'id_menu' => 2,
                'jumlah' => 1,
                'opsi' => 'Ice',
                'catatan' => null,
                'harga' => 7000,
                'subtotal' => 7000,
            ],

            // Pesanan 6: Aren Latte x2 = 16000
            [
                'id_pesanan' => 6,
                'id_menu' => 1,
                'jumlah' => 2,
                'opsi' => 'Ice',
                'catatan' => null,
                'harga' => 8000,
                'subtotal' => 16000,
            ],

            // Pesanan 7: Matcha Latte x1 = 10000
            [
                'id_pesanan' => 7,
                'id_menu' => 3,
                'jumlah' => 1,
                'opsi' => 'Ice',
                'catatan' => null,
                'harga' => 10000,
                'subtotal' => 10000,
            ],

            // Pesanan 8: Coffee Latte x3 = 30000
            [
                'id_pesanan' => 8,
                'id_menu' => 4,
                'jumlah' => 3,
                'opsi' => 'Ice',
                'catatan' => 'Extra es',
                'harga' => 10000,
                'subtotal' => 30000,
            ],

            // Pesanan 9: Aren Latte x2 = 16000
            [
                'id_pesanan' => 9,
                'id_menu' => 1,
                'jumlah' => 2,
                'opsi' => 'Hot',
                'catatan' => null,
                'harga' => 8000,
                'subtotal' => 16000,
            ],

            // Pesanan 10: Americano x1 = 7000
            [
                'id_pesanan' => 10,
                'id_menu' => 2,
                'jumlah' => 1,
                'opsi' => 'Ice',
                'catatan' => null,
                'harga' => 7000,
                'subtotal' => 7000,
            ],
        ]);
    }
}
