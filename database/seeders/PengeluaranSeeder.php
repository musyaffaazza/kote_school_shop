<?php

namespace Database\Seeders;

use App\Models\Pengeluaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengeluaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Pengeluaran::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
