<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pembayaran')->insert([
            [
                'id_pesanan' => 1,
                'metode' => 'QRIS',
                'nominal' => 8000,
                'tanggal_bayar' => '2026-08-01 08:20:00',
                'status' => 'berhasil',
            ],
            [
                'id_pesanan' => 2,
                'metode' => 'Tunai',
                'nominal' => 14000,
                'tanggal_bayar' => '2026-08-01 09:35:00',
                'status' => 'berhasil',
            ],
            [
                'id_pesanan' => 3,
                'metode' => 'Transfer Bank',
                'nominal' => 24000,
                'tanggal_bayar' => '2026-08-02 10:10:00',
                'status' => 'menunggu',
            ],
            [
                'id_pesanan' => 4,
                'metode' => 'QRIS',
                'nominal' => 30000,
                'tanggal_bayar' => '2026-08-02 11:30:00',
                'status' => 'berhasil',
            ],
            [
                'id_pesanan' => 5,
                'metode' => 'Tunai',
                'nominal' => 7000,
                'tanggal_bayar' => '2026-08-03 12:50:00',
                'status' => 'gagal',
            ],
            [
                'id_pesanan' => 6,
                'metode' => 'QRIS',
                'nominal' => 16000,
                'tanggal_bayar' => '2026-08-03 14:15:00',
                'status' => 'berhasil',
            ],
            [
                'id_pesanan' => 7,
                'metode' => 'Transfer Bank',
                'nominal' => 10000,
                'tanggal_bayar' => '2026-08-04 09:05:00',
                'status' => 'menunggu',
            ],
            [
                'id_pesanan' => 8,
                'metode' => 'QRIS',
                'nominal' => 30000,
                'tanggal_bayar' => '2026-08-04 10:35:00',
                'status' => 'berhasil',
            ],
            [
                'id_pesanan' => 9,
                'metode' => 'Tunai',
                'nominal' => 16000,
                'tanggal_bayar' => '2026-08-05 13:20:00',
                'status' => 'berhasil',
            ],
            [
                'id_pesanan' => 10,
                'metode' => 'Transfer Bank',
                'nominal' => 7000,
                'tanggal_bayar' => '2026-08-05 15:10:00',
                'status' => 'menunggu',
            ],
        ]);
    }
}
