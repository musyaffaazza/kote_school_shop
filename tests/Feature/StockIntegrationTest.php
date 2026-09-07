<?php

use App\Models\Menu;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('customer cannot order menu that is out of stock', function () {
    $customer = User::factory()->create(['role' => 'pelanggan']);
    $menu = Menu::create([
        'nama_menu' => 'Espresso Test',
        'kategori' => 'Kopi',
        'harga' => 15000,
        'stok' => 0,
        'status' => 'habis',
        'gambar' => '/images/test.jpg',
    ]);

    $payload = [
        'payment_method' => 'Transfer Bank (BCA)',
        'tipe_pesanan' => 'ambil_di_toko',
        'cart' => [
            [
                'id' => $menu->id_menu,
                'name' => $menu->nama_menu,
                'price' => 15000,
                'qty' => 1,
            ],
        ],
    ];

    $response = $this->actingAs($customer)->postJson(route('orders.store'), $payload);

    $response->assertStatus(422);
    $response->assertJson([
        'success' => false,
    ]);
    expect($response->json('message'))->toContain('habis');
});

test('customer cannot order quantity exceeding available stock', function () {
    $customer = User::factory()->create(['role' => 'pelanggan']);
    $menu = Menu::create([
        'nama_menu' => 'Matcha Test',
        'kategori' => 'Non Kopi',
        'harga' => 18000,
        'stok' => 3,
        'status' => 'tersedia',
        'gambar' => '/images/test.jpg',
    ]);

    $payload = [
        'payment_method' => 'Transfer Bank (BCA)',
        'tipe_pesanan' => 'ambil_di_toko',
        'cart' => [
            [
                'id' => $menu->id_menu,
                'name' => $menu->nama_menu,
                'price' => 18000,
                'qty' => 5,
            ],
        ],
    ];

    $response = $this->actingAs($customer)->postJson(route('orders.store'), $payload);

    $response->assertStatus(422);
    expect($response->json('message'))->toContain('Stok tidak mencukupi');
});

test('customer can create order when stock is sufficient', function () {
    $customer = User::factory()->create(['role' => 'pelanggan']);
    $menu = Menu::create([
        'nama_menu' => 'Caramel Macchiato',
        'kategori' => 'Kopi',
        'harga' => 20000,
        'stok' => 10,
        'status' => 'tersedia',
        'gambar' => '/images/test.jpg',
    ]);

    $payload = [
        'payment_method' => 'Transfer Bank (BCA)',
        'tipe_pesanan' => 'ambil_di_toko',
        'cart' => [
            [
                'id' => $menu->id_menu,
                'name' => $menu->nama_menu,
                'price' => 20000,
                'qty' => 2,
            ],
        ],
    ];

    $response = $this->actingAs($customer)->postJson(route('orders.store'), $payload);

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);

    $this->assertDatabaseHas('pesanan', [
        'id_user' => $customer->id_user,
        'status_pesanan' => 'diproses',
    ]);

    $this->assertDatabaseHas('pembayaran', [
        'metode' => 'Transfer Bank (BCA)',
        'status' => 'menunggu',
    ]);
});

test('karyawan approving payment deducts menu stock and records stock history', function () {
    $karyawan = User::factory()->create(['role' => 'karyawan']);
    $customer = User::factory()->create(['role' => 'pelanggan']);

    $menu = Menu::create([
        'nama_menu' => 'Aren Latte Special',
        'kategori' => 'Kopi',
        'harga' => 15000,
        'stok' => 5,
        'status' => 'tersedia',
        'gambar' => '/images/test.jpg',
    ]);

    $pesanan = Pesanan::create([
        'id_user' => $customer->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 30000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'diproses',
        'tipe_pesanan' => 'ambil_di_toko',
    ]);

    DB::table('detail_pesanan')->insert([
        'id_pesanan' => $pesanan->id_pesanan,
        'id_menu' => $menu->id_menu,
        'jumlah' => 5,
        'harga' => 15000,
        'subtotal' => 30000,
    ]);

    $pembayaran = Pembayaran::create([
        'id_pesanan' => $pesanan->id_pesanan,
        'metode' => 'QRIS',
        'nominal' => 30000,
        'tanggal_bayar' => now(),
        'status' => 'menunggu',
    ]);

    $response = $this->actingAs($karyawan)->post(route('karyawan.verifikasi.setujui', $pembayaran));

    $response->assertRedirect(route('karyawan.verifikasi'));

    $menu->refresh();
    expect($menu->stok)->toBe(0);
    expect($menu->status)->toBe('habis');

    $this->assertDatabaseHas('stok', [
        'id_menu' => $menu->id_menu,
        'stok_keluar' => 5,
        'stok_tersedia' => 0,
    ]);
});

test('admin approving payment deducts menu stock properly', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $customer = User::factory()->create(['role' => 'pelanggan']);

    $menu = Menu::create([
        'nama_menu' => 'Americano Hot',
        'kategori' => 'Kopi',
        'harga' => 12000,
        'stok' => 8,
        'status' => 'tersedia',
        'gambar' => '/images/test.jpg',
    ]);

    $pesanan = Pesanan::create([
        'id_user' => $customer->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 24000,
        'metode_pembayaran' => 'Transfer Bank (BCA)',
        'status_pesanan' => 'diproses',
        'tipe_pesanan' => 'ambil_di_toko',
    ]);

    DB::table('detail_pesanan')->insert([
        'id_pesanan' => $pesanan->id_pesanan,
        'id_menu' => $menu->id_menu,
        'jumlah' => 3,
        'harga' => 12000,
        'subtotal' => 24000,
    ]);

    $pembayaran = Pembayaran::create([
        'id_pesanan' => $pesanan->id_pesanan,
        'metode' => 'Transfer Bank (BCA)',
        'nominal' => 24000,
        'tanggal_bayar' => now(),
        'status' => 'menunggu',
    ]);

    $response = $this->actingAs($admin)->post(route('admin.pembayaran.setujui', $pembayaran));

    $response->assertRedirect(route('admin.pembayaran.index'));

    $menu->refresh();
    expect($menu->stok)->toBe(5);
    expect($menu->status)->toBe('tersedia');
});

test('rejecting payment or cancelling approved order restores deducted stock', function () {
    $karyawan = User::factory()->create(['role' => 'karyawan']);
    $customer = User::factory()->create(['role' => 'pelanggan']);

    $menu = Menu::create([
        'nama_menu' => 'Taro Latte',
        'kategori' => 'Non Kopi',
        'harga' => 16000,
        'stok' => 2,
        'status' => 'tersedia',
        'gambar' => '/images/test.jpg',
    ]);

    $pesanan = Pesanan::create([
        'id_user' => $customer->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 16000,
        'metode_pembayaran' => 'Cash',
        'status_pesanan' => 'diproses',
        'tipe_pesanan' => 'ambil_di_toko',
    ]);

    DB::table('detail_pesanan')->insert([
        'id_pesanan' => $pesanan->id_pesanan,
        'id_menu' => $menu->id_menu,
        'jumlah' => 2,
        'harga' => 16000,
        'subtotal' => 16000,
    ]);

    $pembayaran = Pembayaran::create([
        'id_pesanan' => $pesanan->id_pesanan,
        'metode' => 'Cash',
        'nominal' => 16000,
        'tanggal_bayar' => now(),
        'status' => 'menunggu',
    ]);

    // Approve first (stock becomes 0)
    $this->actingAs($karyawan)->post(route('karyawan.verifikasi.setujui', $pembayaran));
    $menu->refresh();
    expect($menu->stok)->toBe(0);

    // Cancel order status
    $this->actingAs($karyawan)->post(route('karyawan.pesanan.update-status', $pesanan), [
        'status' => 'dibatalkan',
    ]);

    $menu->refresh();
    expect($menu->stok)->toBe(2);
    expect($menu->status)->toBe('tersedia');
});
