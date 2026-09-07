<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            MenuSeeder::class,
            PesananSeeder::class,
            DetailPesananSeeder::class,
            PembayaranSeeder::class,
            StokSeeder::class,
            KaryawanSeeder::class,
            UlasanSeeder::class,
            ContactMessageSeeder::class,
            PromoSeeder::class,
            PengeluaranSeeder::class,
        ]);
    }
}
