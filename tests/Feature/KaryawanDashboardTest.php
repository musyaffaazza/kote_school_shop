<?php

use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('guests are redirected to login when trying to access karyawan dashboard', function () {
    $response = $this->get(route('karyawan.dashboard'));

    $response->assertRedirect(route('login'));
});

test('pelanggan cannot access karyawan dashboard', function () {
    $user = User::factory()->create([
        'role' => 'pelanggan',
    ]);

    $response = $this->actingAs($user)->get(route('karyawan.dashboard'));

    $response->assertStatus(403);
});

test('karyawan can access karyawan dashboard successfully', function () {
    $user = User::factory()->create([
        'role' => 'karyawan',
    ]);

    $response = $this->actingAs($user)->get(route('karyawan.dashboard'));

    $response->assertStatus(200);
    $response->assertSee('Dashboard Karyawan');
    $response->assertSee('Pesanan Terkini');
    $response->assertSee('Tren Penjualan Mingguan');
    $response->assertSee('Volume Pesanan Per Jam');
    $response->assertSee('Distribusi Pesanan');
});

test('admin can access karyawan dashboard successfully', function () {
    $user = User::factory()->create([
        'role' => 'admin',
    ]);

    $response = $this->actingAs($user)->get(route('karyawan.dashboard'));

    $response->assertStatus(200);
    $response->assertSee('Dashboard Karyawan');
});

test('karyawan can access payment verification page successfully', function () {
    $user = User::factory()->create([
        'role' => 'karyawan',
    ]);

    $response = $this->actingAs($user)->get(route('karyawan.verifikasi'));

    $response->assertStatus(200);
    $response->assertSee('Verifikasi Pembayaran');
    $response->assertSee('Pesanan Menunggu Verifikasi');
});

test('karyawan can approve payment successfully', function () {
    $karyawan = User::factory()->create([
        'role' => 'karyawan',
    ]);

    $pelanggan = User::factory()->create([
        'role' => 'pelanggan',
    ]);

    $pesananId = DB::table('pesanan')->insertGetId([
        'id_user' => $pelanggan->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 15000,
        'metode_pembayaran' => 'Transfer',
        'status_pesanan' => 'diproses',
    ]);

    $pembayaranId = DB::table('pembayaran')->insertGetId([
        'id_pesanan' => $pesananId,
        'metode' => 'Transfer',
        'nominal' => 15000,
        'tanggal_bayar' => now(),
        'status' => 'menunggu',
    ]);

    $pembayaran = Pembayaran::find($pembayaranId);

    $response = $this->actingAs($karyawan)->post(route('karyawan.verifikasi.setujui', $pembayaran));

    $response->assertRedirect(route('karyawan.verifikasi'));
    $this->assertDatabaseHas('pembayaran', [
        'id_pembayaran' => $pembayaranId,
        'status' => 'berhasil',
    ]);
    $this->assertDatabaseHas('pesanan', [
        'id_pesanan' => $pesananId,
        'status_pesanan' => 'diproses',
    ]);
});

test('karyawan can reject payment successfully', function () {
    $karyawan = User::factory()->create([
        'role' => 'karyawan',
    ]);

    $pelanggan = User::factory()->create([
        'role' => 'pelanggan',
    ]);

    $pesananId = DB::table('pesanan')->insertGetId([
        'id_user' => $pelanggan->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 15000,
        'metode_pembayaran' => 'Transfer',
        'status_pesanan' => 'diproses',
    ]);

    $pembayaranId = DB::table('pembayaran')->insertGetId([
        'id_pesanan' => $pesananId,
        'metode' => 'Transfer',
        'nominal' => 15000,
        'tanggal_bayar' => now(),
        'status' => 'menunggu',
    ]);

    $pembayaran = Pembayaran::find($pembayaranId);

    $response = $this->actingAs($karyawan)->post(route('karyawan.verifikasi.tolak', $pembayaran));

    $response->assertRedirect(route('karyawan.verifikasi'));
    $this->assertDatabaseHas('pembayaran', [
        'id_pembayaran' => $pembayaranId,
        'status' => 'gagal',
    ]);
    $this->assertDatabaseHas('pesanan', [
        'id_pesanan' => $pesananId,
        'status_pesanan' => 'dibatalkan',
    ]);
});

test('karyawan can access transaction history page successfully', function () {
    $user = User::factory()->create([
        'role' => 'karyawan',
    ]);

    $response = $this->actingAs($user)->get(route('karyawan.riwayat'));

    $response->assertStatus(200);
    $response->assertSee('Riwayat Transaksi');
    $response->assertSee('Rentang Waktu');
    $response->assertSee('Terapkan Filter');
});

test('karyawan can get transaction receipt JSON details successfully', function () {
    $karyawan = User::factory()->create([
        'role' => 'karyawan',
    ]);

    $pelanggan = User::factory()->create([
        'role' => 'pelanggan',
        'nama' => 'Test Customer',
    ]);

    $pesananId = DB::table('pesanan')->insertGetId([
        'id_user' => $pelanggan->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 20000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'selesai',
    ]);

    $response = $this->actingAs($karyawan)->get(route('karyawan.riwayat.struk', $pesananId));

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'pelanggan' => 'Test Customer',
        'kasir' => $karyawan->nama,
        'total' => '20.000',
    ]);
});

test('karyawan can access order list board page successfully', function () {
    $user = User::factory()->create([
        'role' => 'karyawan',
    ]);

    $response = $this->actingAs($user)->get(route('karyawan.pesanan'));

    $response->assertStatus(200);
    $response->assertSee('Daftar Pesanan');
    $response->assertSee('Diproses');
    $response->assertSee('Sedang Dibuat');
    $response->assertSee('Siap Diambil');
});

test('karyawan can update order status successfully via form submission', function () {
    $karyawan = User::factory()->create([
        'role' => 'karyawan',
    ]);

    $pelanggan = User::factory()->create([
        'role' => 'pelanggan',
    ]);

    $pesananId = DB::table('pesanan')->insertGetId([
        'id_user' => $pelanggan->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 15000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'diproses',
    ]);

    $pesanan = Pesanan::find($pesananId);

    $response = $this->actingAs($karyawan)->post(route('karyawan.pesanan.update-status', $pesanan), [
        'status' => 'sedang_dibuat',
    ]);

    $response->assertRedirect(route('karyawan.pesanan'));
    $this->assertDatabaseHas('pesanan', [
        'id_pesanan' => $pesananId,
        'status_pesanan' => 'sedang_dibuat',
    ]);
});

test('unapproved user orders do not enter daftar pesanan until payment is verified and approved', function () {
    $karyawan = User::factory()->create(['role' => 'karyawan']);
    $pelanggan = User::factory()->create(['role' => 'pelanggan', 'nama' => 'Budi Santoso']);

    // 1. Order is created with pending payment (status: menunggu)
    $pesanan = Pesanan::create([
        'id_user' => $pelanggan->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 25000,
        'metode_pembayaran' => 'Transfer Bank',
        'status_pesanan' => 'diproses',
    ]);

    $pembayaran = Pembayaran::create([
        'id_pesanan' => $pesanan->id_pesanan,
        'metode' => 'Transfer Bank',
        'nominal' => 25000,
        'tanggal_bayar' => now(),
        'status' => 'menunggu',
    ]);

    // 2. Karyawan visits Daftar Pesanan -> order must NOT be visible yet!
    $responseBefore = $this->actingAs($karyawan)->get(route('karyawan.pesanan'));
    $responseBefore->assertStatus(200);
    $responseBefore->assertDontSee($pesanan->order_number);

    // 3. Karyawan approves the payment
    $approveResponse = $this->actingAs($karyawan)->post(route('karyawan.verifikasi.setujui', $pembayaran));
    $approveResponse->assertRedirect(route('karyawan.verifikasi'));

    // 4. Now Karyawan visits Daftar Pesanan -> order MUST be visible in the queue!
    $responseAfter = $this->actingAs($karyawan)->get(route('karyawan.pesanan'));
    $responseAfter->assertStatus(200);
    $responseAfter->assertSee($pesanan->order_number);
    $responseAfter->assertSee('Budi Santoso');
});

