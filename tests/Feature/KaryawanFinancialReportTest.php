<?php

use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use App\Models\Pesanan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected to login when accessing karyawan financial report', function () {
    $response = $this->get(route('karyawan.laporan-keuangan.index'));

    $response->assertRedirect(route('login'));
});

test('pelanggan cannot access karyawan financial report', function () {
    $pelanggan = User::factory()->create(['role' => 'pelanggan']);

    $response = $this->actingAs($pelanggan)->get(route('karyawan.laporan-keuangan.index'));

    $response->assertForbidden();
});

test('karyawan can access financial report page with correct summary statistics', function () {
    $karyawan = User::factory()->create(['role' => 'karyawan']);
    $pelanggan = User::factory()->create(['role' => 'pelanggan']);

    // Valid order
    $order = Pesanan::create([
        'id_user' => $pelanggan->id_user,
        'tanggal_pesan' => Carbon::now(),
        'total_harga' => 80000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'selesai',
    ]);
    Pembayaran::create([
        'id_pesanan' => $order->id_pesanan,
        'metode' => 'QRIS',
        'nominal' => 80000,
        'tanggal_bayar' => Carbon::now(),
        'status' => 'berhasil',
    ]);

    // Expense
    Pengeluaran::create([
        'kategori' => 'Bahan Baku',
        'keterangan' => 'Beli Susu Fresh Milk 10L',
        'jumlah' => 25000,
        'tanggal' => Carbon::now()->format('Y-m-d'),
        'metode_pembayaran' => 'Tunai',
        'id_user' => $karyawan->id_user,
    ]);

    $response = $this->actingAs($karyawan)->get(route('karyawan.laporan-keuangan.index'));

    $response->assertSuccessful();
    $response->assertSee('Staff Portal');
    $response->assertSee('Daftar Pesanan');
    $response->assertSee('Verifikasi Pembayaran');
    $response->assertSee('Riwayat Transaksi');
    $response->assertDontSee('Coffee Admin');
    $response->assertSee('Laporan Keuangan');
    $response->assertSee('Grafik Keuangan');
    $response->assertSee('Pengeluaran per Kategori');
    $response->assertSee('Riwayat Harian');
    $response->assertSee('Riwayat Transaksi Pembeli');
    $response->assertSee('Kesimpulan Periode Ini');

    $stats = $response->viewData('stats');
    expect($stats['totalPendapatanRaw'])->toBe(80000);
    expect($stats['totalPengeluaranRaw'])->toBe(25000);
    expect($stats['labaBersihRaw'])->toBe(55000);
    expect($stats['marginLaba'])->toBe(68.75);
});

test('karyawan can filter financial reports by custom date range', function () {
    $karyawan = User::factory()->create(['role' => 'karyawan']);
    $pelanggan = User::factory()->create(['role' => 'pelanggan']);

    // Order 5 days ago
    $pastOrder = Pesanan::create([
        'id_user' => $pelanggan->id_user,
        'tanggal_pesan' => Carbon::today()->subDays(5)->setTime(10, 0),
        'total_harga' => 50000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'selesai',
    ]);
    Pembayaran::create([
        'id_pesanan' => $pastOrder->id_pesanan,
        'metode' => 'QRIS',
        'nominal' => 50000,
        'tanggal_bayar' => Carbon::today()->subDays(5)->setTime(10, 0),
        'status' => 'berhasil',
    ]);

    $response = $this->actingAs($karyawan)->get(route('karyawan.laporan-keuangan.index', [
        'periode' => 'kustom',
        'start_date' => Carbon::today()->subDays(6)->toDateString(),
        'end_date' => Carbon::today()->subDays(4)->toDateString(),
    ]));

    $response->assertSuccessful();
    $stats = $response->viewData('stats');
    expect($stats['totalPendapatanRaw'])->toBe(50000);
});

test('karyawan can record, update, and delete expenses', function () {
    $karyawan = User::factory()->create(['role' => 'karyawan']);

    // 1. Store expense
    $storeResponse = $this->actingAs($karyawan)->post(route('karyawan.laporan-keuangan.pengeluaran.store'), [
        'kategori' => 'Operasional',
        'keterangan' => 'Plastik & Sedotan Ramah Lingkungan',
        'jumlah' => 45000,
        'tanggal' => Carbon::now()->format('Y-m-d'),
        'metode_pembayaran' => 'Tunai',
    ]);

    $storeResponse->assertSessionHas('success');

    $expense = Pengeluaran::where('keterangan', 'Plastik & Sedotan Ramah Lingkungan')->first();
    expect($expense)->not->toBeNull();
    expect((int) $expense->jumlah)->toBe(45000);

    // 2. Update expense
    $updateResponse = $this->actingAs($karyawan)->put(route('karyawan.laporan-keuangan.pengeluaran.update', $expense), [
        'kategori' => 'Operasional',
        'keterangan' => 'Plastik & Sedotan Ramah Lingkungan (Revisi)',
        'jumlah' => 50000,
        'tanggal' => Carbon::now()->format('Y-m-d'),
        'metode_pembayaran' => 'Tunai',
    ]);

    $updateResponse->assertSessionHas('success');
    expect($expense->fresh()->keterangan)->toBe('Plastik & Sedotan Ramah Lingkungan (Revisi)');
    expect((int) $expense->fresh()->jumlah)->toBe(50000);

    // 3. Delete expense
    $deleteResponse = $this->actingAs($karyawan)->delete(route('karyawan.laporan-keuangan.pengeluaran.destroy', $expense));
    $deleteResponse->assertSessionHas('success');

    expect(Pengeluaran::where('id_pengeluaran', $expense->id_pengeluaran)->exists())->toBeFalse();
});

test('karyawan can export financial report to pdf and excel', function () {
    $karyawan = User::factory()->create([
        'role' => 'karyawan',
        'nama' => 'Budi Staff',
        'email' => 'karyawan@koteshop.test',
        'jabatan' => 'Kasir',
    ]);

    // Test PDF export (.pdf)
    $pdfResponse = $this->actingAs($karyawan)->get(route('karyawan.laporan-keuangan.export.pdf'));
    $pdfResponse->assertSuccessful();
    expect($pdfResponse->headers->get('content-type'))->toContain('application/pdf');
    expect($pdfResponse->headers->get('content-disposition'))->toContain('attachment; filename=');

    // Test Excel export (.xls)
    $excelResponse = $this->actingAs($karyawan)->get(route('karyawan.laporan-keuangan.export.excel'));
    $excelResponse->assertSuccessful();
    expect($excelResponse->headers->get('content-type'))->toContain('vnd.ms-excel');
    $excelResponse->assertSee('KOTE SCHOOL SHOP');
});
