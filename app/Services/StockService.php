<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Stok;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * Kurangi stok menu ketika pesanan disetujui/berhasil dibayar.
     * Menggunakan DB Transaction & Row Locking untuk mencegah race condition dan double deduction.
     */
    public static function deductStockForOrder(Pesanan $pesanan): void
    {
        DB::transaction(function () use ($pesanan) {
            // Eager load detail pesanan
            $pesanan->loadMissing('detailPesanan.menu');

            foreach ($pesanan->detailPesanan as $detail) {
                if (! $detail->id_menu) {
                    continue;
                }

                $menu = Menu::where('id_menu', $detail->id_menu)->lockForUpdate()->first();
                if ($menu) {
                    $qtyToDeduct = (int) $detail->jumlah;
                    $newStock = max(0, $menu->stok - $qtyToDeduct);
                    $newStatus = ($newStock <= 0) ? 'habis' : 'tersedia';

                    $menu->update([
                        'stok' => $newStock,
                        'status' => $newStatus,
                    ]);

                    // Catat ke tabel riwayat stok
                    Stok::create([
                        'id_menu' => $menu->id_menu,
                        'stok_masuk' => 0,
                        'stok_keluar' => $qtyToDeduct,
                        'stok_tersedia' => $newStock,
                        'tanggal_update' => now(),
                    ]);
                }
            }
        });
    }

    /**
     * Kembalikan stok menu jika pesanan yang sebelumnya telah memotong stok dibatalkan.
     */
    public static function restoreStockForOrder(Pesanan $pesanan): void
    {
        DB::transaction(function () use ($pesanan) {
            $pesanan->loadMissing('detailPesanan.menu');

            foreach ($pesanan->detailPesanan as $detail) {
                if (! $detail->id_menu) {
                    continue;
                }

                $menu = Menu::where('id_menu', $detail->id_menu)->lockForUpdate()->first();
                if ($menu) {
                    $qtyToRestore = (int) $detail->jumlah;
                    $newStock = $menu->stok + $qtyToRestore;
                    $newStatus = ($newStock > 0) ? 'tersedia' : 'habis';

                    $menu->update([
                        'stok' => $newStock,
                        'status' => $newStatus,
                    ]);

                    // Catat ke tabel riwayat stok
                    Stok::create([
                        'id_menu' => $menu->id_menu,
                        'stok_masuk' => $qtyToRestore,
                        'stok_keluar' => 0,
                        'stok_tersedia' => $newStock,
                        'tanggal_update' => now(),
                    ]);
                }
            }
        });
    }

    /**
     * Update stok menu secara langsung (oleh Admin / Karyawan).
     */
    public static function updateMenuStock(int $menuId, int $newStock, ?string $newStatus = null): Menu
    {
        return DB::transaction(function () use ($menuId, $newStock, $newStatus) {
            $menu = Menu::where('id_menu', $menuId)->lockForUpdate()->firstOrFail();

            $oldStock = (int) $menu->stok;
            $newStock = max(0, $newStock);
            $diff = $newStock - $oldStock;

            $status = $newStatus ?? ($newStock <= 0 ? 'habis' : 'tersedia');
            if ($newStock <= 0) {
                $status = 'habis';
            }

            $menu->update([
                'stok' => $newStock,
                'status' => $status,
            ]);

            // Catat perubahan ke tabel stok
            Stok::create([
                'id_menu' => $menu->id_menu,
                'stok_masuk' => $diff > 0 ? $diff : 0,
                'stok_keluar' => $diff < 0 ? abs($diff) : 0,
                'stok_tersedia' => $newStock,
                'tanggal_update' => now(),
            ]);

            return $menu;
        });
    }
}
