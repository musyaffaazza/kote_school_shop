<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menu')->insert([
            [
                'nama_menu' => 'Aren Latte',
                'kategori' => 'Coffee',
                'harga' => 8000,
                'stok' => 15,
                'deskripsi' => 'Espresso dengan susu dan gula aren alami.',
                'gambar' => '/images/ArenLatte/aren_latte.jpg',
                'status' => 'tersedia',
            ],
            [
                'nama_menu' => 'Americano',
                'kategori' => 'Coffee',
                'harga' => 7000,
                'stok' => 10,
                'deskripsi' => 'Espresso dengan tambahan air dan es batu segar.',
                'gambar' => '/images/Americano/americano.jpg',
                'status' => 'tersedia',
            ],
            [
                'nama_menu' => 'Matcha Latte',
                'kategori' => 'Non Coffee',
                'harga' => 10000,
                'stok' => 20,
                'deskripsi' => 'Minuman matcha premium khas Jepang dengan susu segar.',
                'gambar' => '/images/MatchaLatte/matcha_latte.jpg',
                'status' => 'tersedia',
            ],
            [
                'nama_menu' => 'Coffee Latte',
                'kategori' => 'Coffee',
                'harga' => 10000,
                'stok' => 12,
                'deskripsi' => 'Perpaduan espresso mantap dan susu segar creamy.',
                'gambar' => '/images/CoffeeLatte/coffee_latte.jpg',
                'status' => 'tersedia',
            ],
        ]);
    }
}
