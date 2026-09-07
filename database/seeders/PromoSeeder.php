<?php

namespace Database\Seeders;

use App\Models\Promo;
use Illuminate\Database\Seeder;

class PromoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Promo::truncate();

        $promos = [
            [
                'nama_promo' => 'Nikmati Paket Jumat Aren Latte Beli 2 Hanya Rp15.000',
                'deskripsi' => 'Setiap Hari Jumat',
                'jenis_promo' => 'paket',
                'nilai_promo' => 15000,
                'satuan_nilai' => 'rupiah',
                'kode_voucher' => null,
                'periode_mulai' => now()->startOfYear(),
                'periode_selesai' => null,
                'gambar' => '/images/ArenLatte/aren_latte.jpg',
                'is_active' => true,
            ],
            [
                'nama_promo' => 'Hemat Rp2.000 Bagi yang lahir di Bulan Agustus !!',
                'deskripsi' => 'Cukup tunjukkan KTP atau KIA kamu saat memesan di kasir.',
                'jenis_promo' => 'diskon',
                'nilai_promo' => 2000,
                'satuan_nilai' => 'rupiah',
                'kode_voucher' => 'AGUSTUS2K',
                'periode_mulai' => now()->startOfMonth(),
                'periode_selesai' => now()->addMonths(6),
                'gambar' => '/images/Promo/Agustus.jpeg',
                'is_active' => true,
            ],
            [
                'nama_promo' => 'Potongan Rp2.000 Americano',
                'deskripsi' => 'Pukul 15.00 - 15.30',
                'jenis_promo' => 'diskon',
                'nilai_promo' => 2000,
                'satuan_nilai' => 'rupiah',
                'kode_voucher' => 'HAPPYHOUR',
                'periode_mulai' => now()->startOfYear(),
                'periode_selesai' => null,
                'gambar' => '/images/Americano/americano.jpg',
                'is_active' => true,
            ],
            [
                'nama_promo' => 'Potongan Rp1.000 di semua varian !!',
                'deskripsi' => 'Dapatkan potongan Rp1.000 Bagi pembeli baru !!',
                'jenis_promo' => 'voucher',
                'nilai_promo' => 1000,
                'satuan_nilai' => 'rupiah',
                'kode_voucher' => 'KOTE10',
                'periode_mulai' => now()->startOfYear(),
                'periode_selesai' => now()->addYear(),
                'gambar' => '/images/Promo/AllVariant.jpeg',
                'is_active' => true,
            ],
        ];

        foreach ($promos as $promo) {
            Promo::create($promo);
        }
    }
}
