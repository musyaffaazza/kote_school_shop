<?php

namespace Database\Seeders;

use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PesananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DetailPesanan::truncate();
        Pembayaran::truncate();
        Pesanan::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('pesanan')->insert([
            [
                'id_pesanan' => 1,
                'id_user' => 3,
                'tanggal_pesan' => '2026-08-01 08:15:00',
                'total_harga' => 8000,
                'metode_pembayaran' => 'QRIS',
                'tipe_pesanan' => 'ambil_di_toko',
                'alamat_pengiriman' => null,
                'catatan' => null,
                'status_pesanan' => 'selesai',
            ],
            [
                'id_pesanan' => 2,
                'id_user' => 4,
                'tanggal_pesan' => '2026-08-01 09:30:00',
                'total_harga' => 14000,
                'metode_pembayaran' => 'Tunai',
                'tipe_pesanan' => 'ambil_di_toko',
                'alamat_pengiriman' => null,
                'catatan' => null,
                'status_pesanan' => 'selesai',
            ],
            [
                'id_pesanan' => 3,
                'id_user' => 5,
                'tanggal_pesan' => '2026-08-02 10:05:00',
                'total_harga' => 24000,
                'metode_pembayaran' => 'Transfer Bank',
                'tipe_pesanan' => 'ambil_di_toko',
                'alamat_pengiriman' => null,
                'catatan' => 'Tolong jangan terlalu manis',
                'status_pesanan' => 'diproses',
            ],
            [
                'id_pesanan' => 4,
                'id_user' => 6,
                'tanggal_pesan' => '2026-08-02 11:25:00',
                'total_harga' => 30000,
                'metode_pembayaran' => 'QRIS',
                'tipe_pesanan' => 'ambil_di_toko',
                'alamat_pengiriman' => null,
                'catatan' => null,
                'status_pesanan' => 'selesai',
            ],
            [
                'id_pesanan' => 5,
                'id_user' => 2,
                'tanggal_pesan' => '2026-08-03 12:45:00',
                'total_harga' => 7000,
                'metode_pembayaran' => 'Tunai',
                'tipe_pesanan' => 'ambil_di_toko',
                'alamat_pengiriman' => null,
                'catatan' => null,
                'status_pesanan' => 'dibatalkan',
            ],
            [
                'id_pesanan' => 6,
                'id_user' => 3,
                'tanggal_pesan' => '2026-08-03 14:10:00',
                'total_harga' => 16000,
                'metode_pembayaran' => 'QRIS',
                'tipe_pesanan' => 'ambil_di_toko',
                'alamat_pengiriman' => null,
                'catatan' => null,
                'status_pesanan' => 'selesai',
            ],
            [
                'id_pesanan' => 7,
                'id_user' => 4,
                'tanggal_pesan' => '2026-08-04 09:00:00',
                'total_harga' => 10000,
                'metode_pembayaran' => 'Transfer Bank',
                'tipe_pesanan' => 'ambil_di_toko',
                'alamat_pengiriman' => null,
                'catatan' => null,
                'status_pesanan' => 'diproses',
            ],
            [
                'id_pesanan' => 8,
                'id_user' => 5,
                'tanggal_pesan' => '2026-08-04 10:30:00',
                'total_harga' => 30000,
                'metode_pembayaran' => 'QRIS',
                'tipe_pesanan' => 'ambil_di_toko',
                'alamat_pengiriman' => null,
                'catatan' => 'Extra es',
                'status_pesanan' => 'selesai',
            ],
            [
                'id_pesanan' => 9,
                'id_user' => 6,
                'tanggal_pesan' => '2026-08-05 13:15:00',
                'total_harga' => 16000,
                'metode_pembayaran' => 'Tunai',
                'tipe_pesanan' => 'ambil_di_toko',
                'alamat_pengiriman' => null,
                'catatan' => null,
                'status_pesanan' => 'selesai',
            ],
            [
                'id_pesanan' => 10,
                'id_user' => 2,
                'tanggal_pesan' => '2026-08-05 15:05:00',
                'total_harga' => 7000,
                'metode_pembayaran' => 'Transfer Bank',
                'tipe_pesanan' => 'ambil_di_toko',
                'alamat_pengiriman' => null,
                'catatan' => null,
                'status_pesanan' => 'diproses',
            ],
        ]);
    }
}
