<?php

use App\Models\Promo;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('halaman promo dapat diakses dan menampilkan daftar promo aktif dari database', function () {
    $promo1 = Promo::create([
        'nama_promo' => 'Paket Jumat Aren Latte',
        'deskripsi' => 'Beli 2 hanya Rp15.000',
        'jenis_promo' => 'paket',
        'nilai_promo' => 15000,
        'satuan_nilai' => 'rupiah',
        'kode_voucher' => 'JUMATHEMAT',
        'periode_mulai' => now()->subDay(),
        'periode_selesai' => now()->addMonth(),
        'is_active' => true,
    ]);

    $promo2 = Promo::create([
        'nama_promo' => 'Diskon Pelajar 10%',
        'deskripsi' => 'Diskon 10% untuk pelajar',
        'jenis_promo' => 'diskon',
        'nilai_promo' => 10,
        'satuan_nilai' => 'persen',
        'kode_voucher' => 'PELAJAR10',
        'periode_mulai' => now()->subDay(),
        'periode_selesai' => now()->addMonth(),
        'is_active' => true,
    ]);

    $response = $this->get(route('promo'));

    $response->assertStatus(200);
    $response->assertSee('Promo');
    $response->assertSee('Penawaran Eksklusif');
    $response->assertSee('Paket Jumat Aren Latte');
    $response->assertSee('Diskon Pelajar 10%');
    $response->assertSee('JUMATHEMAT');
    $response->assertSee('PELAJAR10');
});

test('halaman promo menyembunyikan promo yang tidak aktif', function () {
    Promo::create([
        'nama_promo' => 'Promo Aktif',
        'deskripsi' => 'Deskripsi promo aktif',
        'jenis_promo' => 'diskon',
        'nilai_promo' => 2000,
        'satuan_nilai' => 'rupiah',
        'is_active' => true,
    ]);

    Promo::create([
        'nama_promo' => 'Promo Rahasia Nonaktif',
        'deskripsi' => 'Deskripsi promo nonaktif',
        'jenis_promo' => 'diskon',
        'nilai_promo' => 5000,
        'satuan_nilai' => 'rupiah',
        'is_active' => false,
    ]);

    $response = $this->get(route('promo'));

    $response->assertStatus(200);
    $response->assertSee('Promo Aktif');
    $response->assertDontSee('Promo Rahasia Nonaktif');
});

test('halaman promo menyembunyikan promo yang sudah kedaluwarsa', function () {
    Promo::create([
        'nama_promo' => 'Promo Sudah Lewat',
        'deskripsi' => 'Deskripsi promo sudah lewat',
        'jenis_promo' => 'diskon',
        'nilai_promo' => 3000,
        'satuan_nilai' => 'rupiah',
        'periode_mulai' => now()->subMonths(2),
        'periode_selesai' => now()->subDay(),
        'is_active' => true,
    ]);

    $response = $this->get(route('promo'));

    $response->assertStatus(200);
    $response->assertDontSee('Promo Sudah Lewat');
    $response->assertSee('Belum Ada Promo Aktif');
});

test('halaman promo menampilkan empty state jika tidak ada promo aktif', function () {
    $response = $this->get(route('promo'));

    $response->assertStatus(200);
    $response->assertSee('Belum Ada Promo Aktif');
    $response->assertSee('Jelajahi Menu');
});

test('api check promo berhasil memvalidasi kode voucher rupiah', function () {
    Promo::create([
        'nama_promo' => 'Hemat 2 Ribu',
        'deskripsi' => 'Potongan 2000',
        'jenis_promo' => 'diskon',
        'nilai_promo' => 2000,
        'satuan_nilai' => 'rupiah',
        'kode_voucher' => 'HEMAT2K',
        'is_active' => true,
    ]);

    $response = $this->postJson(route('promo.check'), [
        'kode' => 'hemat2k',
        'subtotal' => 20000,
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'discount' => 2000,
    ]);
});

test('api check promo berhasil memvalidasi kode voucher persentase', function () {
    Promo::create([
        'nama_promo' => 'Diskon 10 Persen',
        'deskripsi' => 'Diskon 10%',
        'jenis_promo' => 'voucher',
        'nilai_promo' => 10,
        'satuan_nilai' => 'persen',
        'kode_voucher' => 'KOTE10',
        'is_active' => true,
    ]);

    $response = $this->postJson(route('promo.check'), [
        'kode' => 'KOTE10',
        'subtotal' => 50000,
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'discount' => 5000,
    ]);
});

test('api check promo mengembalikan error jika kode tidak valid atau tidak aktif', function () {
    Promo::create([
        'nama_promo' => 'Promo Mati',
        'jenis_promo' => 'voucher',
        'nilai_promo' => 10,
        'satuan_nilai' => 'persen',
        'kode_voucher' => 'MATI10',
        'is_active' => false,
    ]);

    $response = $this->postJson(route('promo.check'), [
        'kode' => 'MATI10',
        'subtotal' => 50000,
    ]);

    $response->assertStatus(422);
    $response->assertJson([
        'success' => false,
    ]);

    $responseInvalid = $this->postJson(route('promo.check'), [
        'kode' => 'TIDAKADA',
        'subtotal' => 50000,
    ]);

    $responseInvalid->assertStatus(422);
});
