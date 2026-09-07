<?php

use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('guests are redirected to login when trying to access admin pembayaran index', function () {
    $response = $this->get(route('admin.pembayaran.index'));

    $response->assertRedirect(route('login'));
});

test('pelanggan cannot access admin pembayaran index', function () {
    $user = User::factory()->create(['role' => 'pelanggan']);

    $response = $this->actingAs($user)->get(route('admin.pembayaran.index'));

    $response->assertStatus(403);
});

test('karyawan cannot access admin pembayaran index', function () {
    $user = User::factory()->create(['role' => 'karyawan']);

    $response = $this->actingAs($user)->get(route('admin.pembayaran.index'));

    $response->assertStatus(403);
});

test('admin can access admin pembayaran index successfully', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('admin.pembayaran.index'));

    $response->assertStatus(200);
    $response->assertSee('Manajemen Pembayaran');
    $response->assertSee('Riwayat Transaksi');
    $response->assertSee('Metode Pembayaran');
});

test('admin can approve pending payment successfully', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'pelanggan']);

    $pesanan = Pesanan::create([
        'id_user' => $user->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 10000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'diproses',
    ]);

    $pembayaran = Pembayaran::create([
        'id_pesanan' => $pesanan->id_pesanan,
        'metode' => 'QRIS',
        'nominal' => 10000,
        'tanggal_bayar' => now(),
        'status' => 'menunggu',
    ]);

    $response = $this->actingAs($admin)->post(route('admin.pembayaran.setujui', $pembayaran->id_pembayaran));

    $response->assertRedirect();

    $pembayaran->refresh();
    $pesanan->refresh();

    expect($pembayaran->status)->toBe('berhasil');
    expect($pesanan->status_pesanan)->toBe('diproses');
});

test('admin can reject pending payment successfully', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['role' => 'pelanggan']);

    $pesanan = Pesanan::create([
        'id_user' => $user->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 10000,
        'metode_pembayaran' => 'Transfer Bank',
        'status_pesanan' => 'diproses',
    ]);

    $pembayaran = Pembayaran::create([
        'id_pesanan' => $pesanan->id_pesanan,
        'metode' => 'Transfer Bank',
        'nominal' => 10000,
        'tanggal_bayar' => now(),
        'status' => 'menunggu',
    ]);

    $response = $this->actingAs($admin)->post(route('admin.pembayaran.tolak', $pembayaran->id_pembayaran));

    $response->assertRedirect();

    $pembayaran->refresh();
    $pesanan->refresh();

    expect($pembayaran->status)->toBe('gagal');
    expect($pesanan->status_pesanan)->toBe('dibatalkan');
});

test('admin can update payment settings successfully', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    Storage::fake('local');

    $response = $this->actingAs($admin)->post(route('admin.pembayaran.settings'), [
        'method_type' => 'transfer_bank',
        'status' => '1',
        'bank_name' => 'Mandiri',
        'no_rekening' => '999888777',
        'nama_pemilik' => 'Kote Shop Admin Test',
    ]);

    $response->assertRedirect(route('admin.pembayaran.index', ['tab' => 'metode']));

    $settings = Pembayaran::getSettings();
    expect($settings['transfer_bank']['bank_name'])->toBe('Mandiri');
    expect($settings['transfer_bank']['no_rekening'])->toBe('999888777');
    expect($settings['transfer_bank']['nama_pemilik'])->toBe('Kote Shop Admin Test');
    expect($settings['transfer_bank']['status'])->toBeTrue();
});
