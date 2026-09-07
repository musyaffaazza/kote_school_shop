<?php

namespace App\Console\Commands;

use App\Models\DetailPesanan;
use App\Models\Menu;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CleanTransactionData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean and empty all transaction data (pesanan, detail_pesanan, pembayaran) while keeping users and menu intact';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Memulai proses pengosongan data transaksi...');

        $usersCountBefore = User::count();
        $menuCountBefore = Menu::count();
        $pesananCountBefore = Pesanan::count();
        $pembayaranCountBefore = Pembayaran::count();
        $detailPesananCountBefore = DetailPesanan::count();

        $this->line('Data awal:');
        $this->line("- Users: {$usersCountBefore}");
        $this->line("- Menu: {$menuCountBefore}");
        $this->line("- Pesanan: {$pesananCountBefore}");
        $this->line("- Pembayaran: {$pembayaranCountBefore}");
        $this->line("- Detail Pesanan: {$detailPesananCountBefore}");

        DB::transaction(function () {
            // Delete child records first to respect foreign key constraints
            DetailPesanan::query()->delete();
            Pembayaran::query()->delete();
            Pesanan::query()->delete();
        });

        // Reset auto increment counter if supported (DDL outside transaction)
        try {
            $driver = DB::getDriverName();
            if ($driver === 'mysql') {
                DB::statement('ALTER TABLE detail_pesanan AUTO_INCREMENT = 1;');
                DB::statement('ALTER TABLE pembayaran AUTO_INCREMENT = 1;');
                DB::statement('ALTER TABLE pesanan AUTO_INCREMENT = 1;');
            } elseif ($driver === 'sqlite') {
                DB::statement("DELETE FROM sqlite_sequence WHERE name IN ('pesanan', 'pembayaran', 'detail_pesanan');");
            }
        } catch (\Throwable $e) {
            // Ignore if driver does not support resetting auto increment
        }

        // Clean up proof of payment files
        $proofFiles = Storage::disk('public')->files('bukti-pembayaran');
        foreach ($proofFiles as $file) {
            Storage::disk('public')->delete($file);
        }

        $usersCountAfter = User::count();
        $menuCountAfter = Menu::count();
        $pesananCountAfter = Pesanan::count();
        $pembayaranCountAfter = Pembayaran::count();
        $detailPesananCountAfter = DetailPesanan::count();

        $this->newLine();
        $this->info('Pembersihan data transaksi berhasil diselesaikan:');
        $this->line("- Pesanan: {$pesananCountAfter} (dikurangi {$pesananCountBefore})");
        $this->line("- Pembayaran: {$pembayaranCountAfter} (dikurangi {$pembayaranCountBefore})");
        $this->line("- Detail Pesanan: {$detailPesananCountAfter} (dikurangi {$detailPesananCountBefore})");
        $this->line("- Users tetap aman: {$usersCountAfter}");
        $this->line("- Menu tetap aman: {$menuCountAfter}");
    }
}
