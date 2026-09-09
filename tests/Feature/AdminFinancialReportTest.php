<?php

use App\Models\DetailPesanan;
use App\Models\Menu;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use App\Models\Pesanan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('only verified successful transactions are counted towards admin dashboard revenue', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $pelanggan = User::factory()->create(['role' => 'pelanggan']);

    $menu = Menu::create([
        'nama_menu' => 'Kopi Latte',
        'kategori' => 'Kopi',
        'harga' => 10000,
        'stok' => 50,
        'deskripsi' => 'Kopi nikmat',
        'status' => 'tersedia',
    ]);

    // Order A: Rp20.000, Pembayaran berhasil -> masuk
    $orderA = Pesanan::create([
        'id_user' => $pelanggan->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 20000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'diproses',
    ]);
    Pembayaran::create([
        'id_pesanan' => $orderA->id_pesanan,
        'metode' => 'QRIS',
        'nominal' => 20000,
        'tanggal_bayar' => now(),
        'status' => 'berhasil',
    ]);
    DetailPesanan::create([
        'id_pesanan' => $orderA->id_pesanan,
        'id_menu' => $menu->id_menu,
        'jumlah' => 2,
        'harga' => 10000,
        'subtotal' => 20000,
    ]);

    // Order B: Rp15.000, Pembayaran menunggu -> TIDAK masuk
    $orderB = Pesanan::create([
        'id_user' => $pelanggan->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 15000,
        'metode_pembayaran' => 'Transfer Bank',
        'status_pesanan' => 'diproses',
    ]);
    Pembayaran::create([
        'id_pesanan' => $orderB->id_pesanan,
        'metode' => 'Transfer Bank',
        'nominal' => 15000,
        'tanggal_bayar' => now(),
        'status' => 'menunggu',
    ]);
    DetailPesanan::create([
        'id_pesanan' => $orderB->id_pesanan,
        'id_menu' => $menu->id_menu,
        'jumlah' => 1,
        'harga' => 15000,
        'subtotal' => 15000,
    ]);

    // Order C: Rp25.000, Pembayaran gagal -> TIDAK masuk
    $orderC = Pesanan::create([
        'id_user' => $pelanggan->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 25000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'diproses',
    ]);
    Pembayaran::create([
        'id_pesanan' => $orderC->id_pesanan,
        'metode' => 'QRIS',
        'nominal' => 25000,
        'tanggal_bayar' => now(),
        'status' => 'gagal',
    ]);

    // Order D: Rp10.000, Pembayaran ditolak / pesanan dibatalkan -> TIDAK masuk
    $orderD = Pesanan::create([
        'id_user' => $pelanggan->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 10000,
        'metode_pembayaran' => 'Transfer Bank',
        'status_pesanan' => 'dibatalkan',
    ]);
    Pembayaran::create([
        'id_pesanan' => $orderD->id_pesanan,
        'metode' => 'Transfer Bank',
        'nominal' => 10000,
        'tanggal_bayar' => now(),
        'status' => 'gagal',
    ]);

    // Order E: Rp30.000, Pembayaran berhasil -> masuk
    $orderE = Pesanan::create([
        'id_user' => $pelanggan->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 30000,
        'metode_pembayaran' => 'Tunai',
        'status_pesanan' => 'selesai',
    ]);
    Pembayaran::create([
        'id_pesanan' => $orderE->id_pesanan,
        'metode' => 'Tunai',
        'nominal' => 30000,
        'tanggal_bayar' => now(),
        'status' => 'berhasil',
    ]);
    DetailPesanan::create([
        'id_pesanan' => $orderE->id_pesanan,
        'id_menu' => $menu->id_menu,
        'jumlah' => 3,
        'harga' => 10000,
        'subtotal' => 30000,
    ]);

    // Total pendapatan should be Rp20.000 + Rp30.000 = Rp50.000
    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertStatus(200);

    // Verify statistics passed to view
    $stats = $response->viewData('stats');
    expect($stats['pendapatanHariIniRaw'])->toBe(50000);
    expect($stats['pendapatanHariIni'])->toBe('Rp50.000');
    expect($stats['totalPenjualanRaw'])->toBe(50000);
    expect($stats['totalTransaksiRaw'])->toBe(2);
    expect($stats['produkTerjualRaw'])->toBe(5); // 2 from Order A + 3 from Order E
});

test('cancelled order is not counted in revenue even if payment was marked berhasil', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $pelanggan = User::factory()->create(['role' => 'pelanggan']);

    $order = Pesanan::create([
        'id_user' => $pelanggan->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 45000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'dibatalkan', // Cancelled order
    ]);

    Pembayaran::create([
        'id_pesanan' => $order->id_pesanan,
        'metode' => 'QRIS',
        'nominal' => 45000,
        'tanggal_bayar' => now(),
        'status' => 'berhasil',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertStatus(200);
    $stats = $response->viewData('stats');
    expect($stats['pendapatanHariIniRaw'])->toBe(0);
    expect($stats['totalPenjualanRaw'])->toBe(0);
    expect($stats['totalTransaksiRaw'])->toBe(0);
});

test('admin dashboard calculates today, this week, and this month revenue dynamically', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $pelanggan = User::factory()->create(['role' => 'pelanggan']);

    // 1. Transaction Today (Rp25.000)
    $orderToday = Pesanan::create([
        'id_user' => $pelanggan->id_user,
        'tanggal_pesan' => Carbon::today()->addHours(10),
        'total_harga' => 25000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'diproses',
    ]);
    Pembayaran::create([
        'id_pesanan' => $orderToday->id_pesanan,
        'metode' => 'QRIS',
        'nominal' => 25000,
        'tanggal_bayar' => Carbon::today()->addHours(10),
        'status' => 'berhasil',
    ]);

    // 2. Transaction Yesterday (if in same week/month) or 2 days ago (Rp35.000)
    // We set a transaction strictly in this week but not today
    $dayInThisWeekNotToday = Carbon::now()->startOfWeek()->isToday()
        ? Carbon::now()->endOfWeek()
        : Carbon::now()->startOfWeek();

    // If dayInThisWeekNotToday is today, let's pick another day in week
    if ($dayInThisWeekNotToday->isToday()) {
        $dayInThisWeekNotToday = Carbon::today()->addDay();
    }

    $orderThisWeek = Pesanan::create([
        'id_user' => $pelanggan->id_user,
        'tanggal_pesan' => $dayInThisWeekNotToday,
        'total_harga' => 35000,
        'metode_pembayaran' => 'Transfer Bank',
        'status_pesanan' => 'selesai',
    ]);
    Pembayaran::create([
        'id_pesanan' => $orderThisWeek->id_pesanan,
        'metode' => 'Transfer Bank',
        'nominal' => 35000,
        'tanggal_bayar' => $dayInThisWeekNotToday,
        'status' => 'berhasil',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertStatus(200);
    $stats = $response->viewData('stats');

    expect($stats['pendapatanHariIniRaw'])->toBe(25000);
    expect($stats['totalPenjualanRaw'])->toBe(60000);
    expect($stats['totalTransaksiRaw'])->toBe(2);
});

test('admin dashboard filters transactions by date range, payment method, payment status, and search query', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $userAlice = User::factory()->create(['role' => 'pelanggan', 'nama' => 'Alice Wonder']);
    $userBob = User::factory()->create(['role' => 'pelanggan', 'nama' => 'Bob Builder']);

    // Order 1: Alice, QRIS, Berhasil, Today
    $order1 = Pesanan::create([
        'id_user' => $userAlice->id_user,
        'tanggal_pesan' => Carbon::today()->setTime(9, 0),
        'total_harga' => 20000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'selesai',
    ]);
    Pembayaran::create([
        'id_pesanan' => $order1->id_pesanan,
        'metode' => 'QRIS',
        'nominal' => 20000,
        'tanggal_bayar' => Carbon::today()->setTime(9, 0),
        'status' => 'berhasil',
    ]);

    // Order 2: Bob, Tunai, Menunggu, Today
    $order2 = Pesanan::create([
        'id_user' => $userBob->id_user,
        'tanggal_pesan' => Carbon::today()->setTime(11, 0),
        'total_harga' => 15000,
        'metode_pembayaran' => 'Tunai',
        'status_pesanan' => 'diproses',
    ]);
    Pembayaran::create([
        'id_pesanan' => $order2->id_pesanan,
        'metode' => 'Tunai',
        'nominal' => 15000,
        'tanggal_bayar' => Carbon::today()->setTime(11, 0),
        'status' => 'menunggu',
    ]);

    // Order 3: Alice, Transfer Bank, Gagal, 10 days ago
    $order3 = Pesanan::create([
        'id_user' => $userAlice->id_user,
        'tanggal_pesan' => Carbon::today()->subDays(10)->setTime(14, 0),
        'total_harga' => 50000,
        'metode_pembayaran' => 'Transfer Bank',
        'status_pesanan' => 'dibatalkan',
    ]);
    Pembayaran::create([
        'id_pesanan' => $order3->id_pesanan,
        'metode' => 'Transfer Bank',
        'nominal' => 50000,
        'tanggal_bayar' => Carbon::today()->subDays(10)->setTime(14, 0),
        'status' => 'gagal',
    ]);

    // Test Search filter by name
    $searchResponse = $this->actingAs($admin)->get(route('admin.dashboard', ['search' => 'Alice']));
    $searchResponse->assertStatus(200);
    $transactions = $searchResponse->viewData('transaksiTerbaru');
    expect($transactions->total())->toBe(2);

    // Test Payment Method filter (QRIS)
    $methodResponse = $this->actingAs($admin)->get(route('admin.dashboard', ['metode' => 'qris']));
    $methodResponse->assertStatus(200);
    $transactions = $methodResponse->viewData('transaksiTerbaru');
    expect($transactions->total())->toBe(1);
    expect($transactions->first()->id_pesanan)->toBe($order1->id_pesanan);

    // Test Payment Status filter (menunggu)
    $statusResponse = $this->actingAs($admin)->get(route('admin.dashboard', ['status_pembayaran' => 'menunggu']));
    $statusResponse->assertStatus(200);
    $transactions = $statusResponse->viewData('transaksiTerbaru');
    expect($transactions->total())->toBe(1);
    expect($transactions->first()->id_pesanan)->toBe($order2->id_pesanan);

    // Test Date Range Filter
    $rangeResponse = $this->actingAs($admin)->get(route('admin.dashboard', [
        'start_date' => Carbon::today()->subDays(12)->toDateString(),
        'end_date' => Carbon::today()->subDays(8)->toDateString(),
    ]));
    $rangeResponse->assertStatus(200);
    $transactions = $rangeResponse->viewData('transaksiTerbaru');
    expect($transactions->total())->toBe(1);
    expect($transactions->first()->id_pesanan)->toBe($order3->id_pesanan);
});

test('end-to-end customer order creation and verification flow updates financial reports dynamically', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $pelanggan = User::factory()->create(['role' => 'pelanggan']);
    $karyawan = User::factory()->create(['role' => 'karyawan']);

    $menu = Menu::create([
        'nama_menu' => 'Espresso Hot',
        'kategori' => 'Kopi',
        'harga' => 18000,
        'stok' => 20,
        'deskripsi' => 'Espresso pekat',
        'status' => 'tersedia',
    ]);

    // 1. Initial State: Revenue is 0
    $initialResponse = $this->actingAs($admin)->get(route('admin.dashboard'));
    expect($initialResponse->viewData('stats')['pendapatanHariIniRaw'])->toBe(0);

    // 2. Customer creates order via OrderController
    $orderResponse = $this->actingAs($pelanggan)->postJson(route('orders.store'), [
        'payment_method' => 'QRIS',
        'cart' => [
            [
                'id' => $menu->id_menu,
                'qty' => 2,
                'price' => 18000,
            ],
        ],
    ]);
    $orderResponse->assertStatus(200);

    $createdOrder = Pesanan::latest('id_pesanan')->first();
    expect($createdOrder)->not->toBeNull();
    // Subtotal 36000 + service fee 2000 = 38000
    expect((int) $createdOrder->total_harga)->toBe(38000);

    // 3. Before payment verification: Status is menunggu -> revenue must STILL be 0
    $afterOrderResponse = $this->actingAs($admin)->get(route('admin.dashboard'));
    expect($afterOrderResponse->viewData('stats')['pendapatanHariIniRaw'])->toBe(0);
    expect($afterOrderResponse->viewData('stats')['totalTransaksiRaw'])->toBe(0);

    // 4. Karyawan verifies and approves the payment
    $pembayaran = $createdOrder->pembayaran;
    $approveResponse = $this->actingAs($karyawan)->post(route('karyawan.verifikasi.setujui', $pembayaran));
    $approveResponse->assertRedirect(route('karyawan.verifikasi'));

    // 5. After verification: Admin dashboard AUTOMATICALLY includes Rp38.000 in revenue!
    $afterApproveResponse = $this->actingAs($admin)->get(route('admin.dashboard'));
    expect($afterApproveResponse->viewData('stats')['pendapatanHariIniRaw'])->toBe(38000);
    expect($afterApproveResponse->viewData('stats')['totalTransaksiRaw'])->toBe(1);
    expect($afterApproveResponse->viewData('stats')['produkTerjualRaw'])->toBe(2);
});

test('chart data contains dynamic aggregated figures from valid transactions', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $pelanggan = User::factory()->create(['role' => 'pelanggan']);

    // Order 3 days ago (Rp40.000)
    $order1 = Pesanan::create([
        'id_user' => $pelanggan->id_user,
        'tanggal_pesan' => Carbon::today()->subDays(3)->setTime(10, 0),
        'total_harga' => 40000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'selesai',
    ]);
    Pembayaran::create([
        'id_pesanan' => $order1->id_pesanan,
        'metode' => 'QRIS',
        'nominal' => 40000,
        'tanggal_bayar' => Carbon::today()->subDays(3)->setTime(10, 0),
        'status' => 'berhasil',
    ]);

    // Order Today (Rp60.000)
    $order2 = Pesanan::create([
        'id_user' => $pelanggan->id_user,
        'tanggal_pesan' => Carbon::today()->setTime(15, 0),
        'total_harga' => 60000,
        'metode_pembayaran' => 'Tunai',
        'status_pesanan' => 'diproses',
    ]);
    Pembayaran::create([
        'id_pesanan' => $order2->id_pesanan,
        'metode' => 'Tunai',
        'nominal' => 60000,
        'tanggal_bayar' => Carbon::today()->setTime(15, 0),
        'status' => 'berhasil',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));
    $response->assertStatus(200);

    $chartData = $response->viewData('chartData');
    expect($chartData)->toHaveKeys([7, 14, 30, 'month', 'year']);

    // 7 days chart should have 7 elements and sum to 100.000
    expect(count($chartData[7]['labels']))->toBe(7);
    expect(array_sum($chartData[7]['revenue']))->toBe(100000);
    expect(array_sum($chartData[7]['transactions']))->toBe(2);
});

test('karyawan dashboard displays operational summary correctly', function () {
    $karyawan = User::factory()->create(['role' => 'karyawan']);
    $pelanggan = User::factory()->create(['role' => 'pelanggan']);

    // 1. Order today - Berhasil (Rp30.000)
    $order1 = Pesanan::create([
        'id_user' => $pelanggan->id_user,
        'tanggal_pesan' => Carbon::today()->setTime(8, 30),
        'total_harga' => 30000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'selesai',
    ]);
    Pembayaran::create([
        'id_pesanan' => $order1->id_pesanan,
        'metode' => 'QRIS',
        'nominal' => 30000,
        'tanggal_bayar' => Carbon::today()->setTime(8, 30),
        'status' => 'berhasil',
    ]);

    // 2. Order today - Menunggu verifikasi (Rp20.000)
    $order2 = Pesanan::create([
        'id_user' => $pelanggan->id_user,
        'tanggal_pesan' => Carbon::today()->setTime(9, 0),
        'total_harga' => 20000,
        'metode_pembayaran' => 'Transfer Bank',
        'status_pesanan' => 'diproses',
    ]);
    Pembayaran::create([
        'id_pesanan' => $order2->id_pesanan,
        'metode' => 'Transfer Bank',
        'nominal' => 20000,
        'tanggal_bayar' => Carbon::today()->setTime(9, 0),
        'status' => 'menunggu',
    ]);

    $response = $this->actingAs($karyawan)->get(route('karyawan.dashboard'));
    $response->assertStatus(200);

    $stats = $response->viewData('operationalStats');
    expect($stats['transaksiHariIni'])->toBe(1);
    expect($stats['pesananHariIni'])->toBe(2);
    expect($stats['pendapatanHariIni'])->toBe('Rp30.000');
    expect($stats['menungguVerifikasi'])->toBe(1);
    expect($stats['pesananSelesai'])->toBe(1);
});

test('admin can view financial report page with net profit, expenses per category, and chart data', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $pelanggan = User::factory()->create(['role' => 'pelanggan']);

    // Order
    $order = Pesanan::create([
        'id_user' => $pelanggan->id_user,
        'tanggal_pesan' => Carbon::now(),
        'total_harga' => 100000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'selesai',
    ]);
    Pembayaran::create([
        'id_pesanan' => $order->id_pesanan,
        'metode' => 'QRIS',
        'nominal' => 100000,
        'tanggal_bayar' => Carbon::now(),
        'status' => 'berhasil',
    ]);

    // Expense
    Pengeluaran::create([
        'kategori' => 'Bahan Baku',
        'keterangan' => 'Beli Biji Kopi Arabika 2kg',
        'jumlah' => 30000,
        'tanggal' => Carbon::now()->format('Y-m-d'),
        'metode_pembayaran' => 'Tunai',
        'id_user' => $admin->id_user,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.laporan-keuangan.index'));

    $response->assertStatus(200);
    $response->assertSee('Coffee Admin');
    $response->assertDontSee('Staff Portal');
    $response->assertSee('Laporan Keuangan');
    $response->assertSee('Grafik Keuangan');
    $response->assertSee('Pengeluaran per Kategori');
    $response->assertSee('Riwayat Harian');
    $response->assertSee('Riwayat Transaksi Pembeli');
    $response->assertSee('Kesimpulan Periode Ini');

    $stats = $response->viewData('stats');
    expect($stats['totalPendapatanRaw'])->toBe(100000);
    expect($stats['totalPengeluaranRaw'])->toBe(30000);
    expect($stats['labaBersihRaw'])->toBe(70000);
    expect($stats['marginLaba'])->toBe(70.0);
});

test('admin can record and delete new expenses', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    // Store Expense
    $response = $this->actingAs($admin)->post(route('admin.laporan-keuangan.pengeluaran.store'), [
        'kategori' => 'Operasional',
        'keterangan' => 'Beli Cup Takeaway 500 pcs',
        'jumlah' => 150000,
        'tanggal' => Carbon::now()->format('Y-m-d'),
        'metode_pembayaran' => 'Transfer Bank',
    ]);

    $response->assertSessionHas('success');

    $expense = Pengeluaran::where('keterangan', 'Beli Cup Takeaway 500 pcs')->first();
    expect($expense)->not->toBeNull();
    expect((int) $expense->jumlah)->toBe(150000);

    // Delete Expense
    $deleteResponse = $this->actingAs($admin)->delete(route('admin.laporan-keuangan.pengeluaran.destroy', $expense));
    $deleteResponse->assertSessionHas('success');

    expect(Pengeluaran::where('keterangan', 'Beli Cup Takeaway 500 pcs')->exists())->toBeFalse();
});

test('admin can export financial report to pdf and excel', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'nama' => 'Azza Administrator',
        'email' => 'admin@koteshop.test',
    ]);

    // Test PDF export (.pdf)
    $pdfResponse = $this->actingAs($admin)->get(route('admin.laporan-keuangan.export.pdf'));
    $pdfResponse->assertStatus(200);
    expect($pdfResponse->headers->get('content-type'))->toContain('application/pdf');
    expect($pdfResponse->headers->get('content-disposition'))->toContain('attachment; filename=');

    // Test Excel export (.xls)
    $excelResponse = $this->actingAs($admin)->get(route('admin.laporan-keuangan.export.excel'));
    $excelResponse->assertStatus(200);
    expect($excelResponse->headers->get('content-type'))->toContain('vnd.ms-excel');
    $excelResponse->assertSee('KOTE SCHOOL SHOP');
});

test('financial report chart does not display future days and drops to zero on days without transactions', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $pelanggan = User::factory()->create(['role' => 'pelanggan']);

    // Create an order on day 1 of this month
    $day1 = Carbon::now()->startOfMonth()->setTime(10, 0);
    $order1 = Pesanan::create([
        'id_user' => $pelanggan->id_user,
        'tanggal_pesan' => $day1,
        'total_harga' => 50000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'selesai',
    ]);
    Pembayaran::create([
        'id_pesanan' => $order1->id_pesanan,
        'metode' => 'QRIS',
        'nominal' => 50000,
        'tanggal_bayar' => $day1,
        'status' => 'berhasil',
    ]);

    // Expense on day 2 of this month
    $day2 = Carbon::now()->startOfMonth()->addDays(1)->format('Y-m-d');
    Pengeluaran::create([
        'kategori' => 'Bahan Baku',
        'keterangan' => 'Beli Susu',
        'jumlah' => 20000,
        'tanggal' => $day2,
        'metode_pembayaran' => 'Tunai',
        'id_user' => $admin->id_user,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.laporan-keuangan.index', ['periode' => 'bulan_ini']));
    $response->assertStatus(200);

    $chartData = $response->viewData('chartData');
    
    // Check that labels only extend up to today, not the full 30/31 days of month if today < month end
    $expectedDaysCount = Carbon::today()->day;
    expect(count($chartData['labels']))->toBe($expectedDaysCount);

    // Day 1 has 50k revenue, 0 expense
    expect($chartData['pemasukan_harian'][0])->toBe(50000);
    expect($chartData['pengeluaran_harian'][0])->toBe(0);

    // If today is day 3 or later, days with no orders must have 0 (chart drops to 0)
    if ($expectedDaysCount >= 3) {
        expect($chartData['pemasukan_harian'][2])->toBe(0);
        expect($chartData['pengeluaran_harian'][2])->toBe(0);
    }

    // Default 'pemasukan' array should be daily (dropping to zero on empty days)
    expect($chartData['pemasukan'])->toEqual($chartData['pemasukan_harian']);
    expect($chartData['pengeluaran'])->toEqual($chartData['pengeluaran_harian']);
});

