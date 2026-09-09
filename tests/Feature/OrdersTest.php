<?php

use App\Models\Menu;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Promo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('guests are redirected to login when trying to access orders index page', function () {
    $response = $this->get(route('orders.index'));

    $response->assertRedirect(route('login'));
});

test('guests are redirected to login when trying to access order details page', function () {
    $user = User::factory()->create();
    $pesanan = Pesanan::create([
        'id_user' => $user->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 17000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'diproses',
    ]);

    $response = $this->get(route('orders.show', $pesanan->id_pesanan));

    $response->assertRedirect(route('login'));
});

test('logged in user can view their orders index page', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('orders.index'));

    $response->assertStatus(200);
});

test('logged in user can view their order detail page with successful payment layout', function () {
    $user = User::factory()->create([
        'nama' => 'Azza Musyaffa',
    ]);

    $pesanan = Pesanan::create([
        'id_user' => $user->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 17000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'diproses',
    ]);

    $pembayaran = Pembayaran::create([
        'id_pesanan' => $pesanan->id_pesanan,
        'metode' => 'QRIS',
        'nominal' => 17000,
        'tanggal_bayar' => now(),
        'status' => 'berhasil',
    ]);

    // Create a dummy menu item to associate with detailed order
    $menu = Menu::create([
        'nama_menu' => 'Aren Latte',
        'harga' => 8000,
        'deskripsi' => 'Kopi susu gula aren premium',
        'gambar' => '/images/ArenLatte/aren_latte.jpg',
        'kategori' => 'Kopi',
        'stok' => 10,
        'status' => 'tersedia',
    ]);

    DB::table('detail_pesanan')->insert([
        'id_pesanan' => $pesanan->id_pesanan,
        'id_menu' => $menu->id_menu,
        'jumlah' => 1,
        'harga' => 8000,
        'subtotal' => 8000,
    ]);

    $response = $this->actingAs($user)->get(route('orders.show', $pesanan->id_pesanan).'?receipt=true');

    $response->assertStatus(200);
    $response->assertSee('Pembayaran Berhasil');
    $response->assertSee('Aren Latte');
    $response->assertSee('ORDER NUMBER');
    $response->assertSee('TOTAL AMOUNT');
    $response->assertSee('QRIS');
});

test('user cannot view details of other user\'s order', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $pesanan = Pesanan::create([
        'id_user' => $user1->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 17000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'diproses',
    ]);

    $response = $this->actingAs($user2)->get(route('orders.show', $pesanan->id_pesanan));

    $response->assertStatus(403);
});

test('order with sedang_dibuat and siap_diambil statuses displays correct badges on index page', function () {
    $user = User::factory()->create();

    $pesanan1 = Pesanan::create([
        'id_user' => $user->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 15000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'sedang_dibuat',
    ]);

    $pesanan2 = Pesanan::create([
        'id_user' => $user->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 20000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'siap_diambil',
    ]);

    $response = $this->actingAs($user)->get(route('orders.index'));

    $response->assertStatus(200);
    $response->assertSee('Sedang Dibuat');
    $response->assertSee('Siap Diambil');

    // Filtering by 'diproses' tab should also return in-progress orders
    $filteredResponse = $this->actingAs($user)->get(route('orders.index', ['status' => 'diproses']));
    $filteredResponse->assertStatus(200);
    $filteredResponse->assertSee($pesanan1->order_number);
    $filteredResponse->assertSee($pesanan2->order_number);
});

test('order tracking page displays sedang_dibuat and siap_diambil status correctly', function () {
    $user = User::factory()->create();

    $pesanan = Pesanan::create([
        'id_user' => $user->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 18000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'sedang_dibuat',
    ]);

    $pembayaran = Pembayaran::create([
        'id_pesanan' => $pesanan->id_pesanan,
        'metode' => 'QRIS',
        'nominal' => 18000,
        'tanggal_bayar' => now(),
        'status' => 'berhasil',
    ]);

    $response = $this->actingAs($user)->get(route('orders.show', $pesanan->id_pesanan));

    $response->assertStatus(200);
    $response->assertSee('Sedang Dibuat');
    $response->assertSee('NOMOR ANTRIAN ANDA');
    $response->assertSee('Lacak Pesanan');
});

test('storing an order preserves item notes and order note and displays on employee dashboard', function () {
    $user = User::factory()->create();
    $menu = Menu::create([
        'nama_menu' => 'Matcha Latte',
        'harga' => 12000,
        'deskripsi' => 'Matcha premium',
        'gambar' => '/images/matcha.jpg',
        'kategori' => 'Non-Kopi',
        'stok' => 10,
        'status' => 'tersedia',
    ]);

    $payload = [
        'payment_method' => 'QRIS',
        'catatan' => 'Tolong dipisah sedotannya',
        'cart' => [
            [
                'id' => $menu->id_menu,
                'qty' => 2,
                'price' => 12000,
                'sugar' => '50%',
                'ice' => 'Less',
                'toppings' => ['Extra Shot'],
                'notes' => 'Jangan terlalu manis',
            ],
        ],
    ];

    $response = $this->actingAs($user)->postJson(route('orders.store'), $payload);

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);

    $pesanan = Pesanan::latest('id_pesanan')->first();
    expect($pesanan)->not->toBeNull();
    expect($pesanan->catatan)->toBe('Tolong dipisah sedotannya');

    $detail = $pesanan->detailPesanan()->first();
    expect($detail)->not->toBeNull();
    expect($detail->catatan)->toBe('Jangan terlalu manis');
    expect($detail->opsi)->toContain('Gula 50%');
    expect($detail->opsi)->toContain('Less Ice');
    expect($detail->opsi)->toContain('Extra Shot');

    // As employee, view pesanan dashboard
    $karyawan = User::factory()->create(['role' => 'karyawan']);
    $pesanan->pembayaran->update(['status' => 'berhasil']);
    $employeeResponse = $this->actingAs($karyawan)->get(route('karyawan.pesanan'));

    $employeeResponse->assertStatus(200);
    $employeeResponse->assertSee('Tolong dipisah sedotannya');
    $employeeResponse->assertSee('Jangan terlalu manis');
    $employeeResponse->assertSee('Gula 50%');
});

test('cash payment page displays Detail Pembayaran Tunai with order number and cash instructions', function () {
    $user = User::factory()->create();

    $pesanan = Pesanan::create([
        'id_user' => $user->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 34000,
        'metode_pembayaran' => 'Tunai',
        'status_pesanan' => 'diproses',
    ]);

    $pembayaran = Pembayaran::create([
        'id_pesanan' => $pesanan->id_pesanan,
        'metode' => 'Tunai',
        'nominal' => 34000,
        'tanggal_bayar' => now(),
        'status' => 'menunggu',
    ]);

    $menu = Menu::create([
        'nama_menu' => 'Aren Latte',
        'harga' => 8000,
        'deskripsi' => 'Kopi susu gula aren',
        'gambar' => '/images/ArenLatte/aren_latte.jpg',
        'kategori' => 'Kopi',
        'stok' => 10,
        'status' => 'tersedia',
    ]);

    DB::table('detail_pesanan')->insert([
        'id_pesanan' => $pesanan->id_pesanan,
        'id_menu' => $menu->id_menu,
        'jumlah' => 1,
        'opsi' => 'Gula Sedang',
        'harga' => 8000,
        'subtotal' => 8000,
    ]);

    $response = $this->actingAs($user)->get(route('payment', $pesanan->id_pesanan));

    $response->assertStatus(200);
    $response->assertSee('Detail Pembayaran Tunai');
    $response->assertSee('Selesaikan pesanan Anda di kasir.');
    $response->assertSee('Ringkasan Pesanan');
    $response->assertSee('Aren Latte');
    $response->assertSee('Gula Sedang');
    $response->assertSee('Pembayaran Tunai');
    $response->assertSee('Silakan lakukan pembayaran di kasir dengan menyebutkan nomor pesanan Anda.');
    $response->assertSee('NOMOR PESANAN');
    $response->assertSee($pesanan->order_number);
    $response->assertSee('Konfirmasi Pesanan');
});

test('cash payment page can be accessed with query parameter metode=tunai', function () {
    $response = $this->get(route('payment', ['metode' => 'tunai']));

    $response->assertStatus(200);
    $response->assertSee('Detail Pembayaran Tunai');
    $response->assertSee('Selesaikan pesanan Anda di kasir.');
    $response->assertSee('Pembayaran Tunai');
    $response->assertSee('NOMOR PESANAN');
    $response->assertSee('Konfirmasi Pesanan');
});

test('user attempting to access order tracking before payment is verified is redirected to payment page', function () {
    $user = User::factory()->create();

    $pesanan = Pesanan::create([
        'id_user' => $user->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 34000,
        'metode_pembayaran' => 'Tunai',
        'status_pesanan' => 'diproses',
    ]);

    Pembayaran::create([
        'id_pesanan' => $pesanan->id_pesanan,
        'metode' => 'Tunai',
        'nominal' => 34000,
        'tanggal_bayar' => now(),
        'status' => 'menunggu',
    ]);

    $response = $this->actingAs($user)->get(route('orders.show', $pesanan->id_pesanan));

    $response->assertRedirect(route('payment', $pesanan->id_pesanan));
});

test('user can access order tracking once employee approves cash payment', function () {
    $user = User::factory()->create();

    $pesanan = Pesanan::create([
        'id_user' => $user->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 34000,
        'metode_pembayaran' => 'Tunai',
        'status_pesanan' => 'diproses',
    ]);

    $pembayaran = Pembayaran::create([
        'id_pesanan' => $pesanan->id_pesanan,
        'metode' => 'Tunai',
        'nominal' => 34000,
        'tanggal_bayar' => now(),
        'status' => 'menunggu',
    ]);

    $karyawan = User::factory()->create(['role' => 'karyawan']);

    // Employee approves the payment
    $this->actingAs($karyawan)->post(route('karyawan.verifikasi.setujui', $pembayaran->id_pembayaran));

    // Customer can now access order tracking
    $response = $this->actingAs($user)->get(route('orders.show', $pesanan->id_pesanan));

    $response->assertStatus(200);
    $response->assertSee('Detail Pesanan');
    $response->assertSee('NOMOR ANTRIAN ANDA');
});

test('logged in user can delete their order from order history', function () {
    Storage::fake('public');

    $user = User::factory()->create();

    $pesanan = Pesanan::create([
        'id_user' => $user->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 25000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'selesai',
    ]);

    // Create related detail_pesanan
    $menu = Menu::create([
        'nama_menu' => 'Kopi Tubruk',
        'harga' => 10000,
        'deskripsi' => 'Kopi hitam tradisional',
        'gambar' => '/images/kopi.jpg',
        'kategori' => 'Kopi',
        'stok' => 10,
        'status' => 'tersedia',
    ]);

    DB::table('detail_pesanan')->insert([
        'id_pesanan' => $pesanan->id_pesanan,
        'id_menu' => $menu->id_menu,
        'jumlah' => 2,
        'harga' => 10000,
        'subtotal' => 20000,
    ]);

    // Create fake proof of payment file
    Storage::disk('public')->put('bukti-pembayaran/test.jpg', 'fake content');

    Pembayaran::create([
        'id_pesanan' => $pesanan->id_pesanan,
        'metode' => 'QRIS',
        'nominal' => 25000,
        'tanggal_bayar' => now(),
        'status' => 'berhasil',
        'bukti_transfer' => '/storage/bukti-pembayaran/test.jpg',
    ]);

    $response = $this->actingAs($user)->delete(route('orders.destroy', $pesanan->id_pesanan));

    $response->assertRedirect(route('orders.index'));
    $response->assertSessionHas('success', 'Riwayat pesanan berhasil dihapus.');

    $this->assertDatabaseMissing('pesanan', [
        'id_pesanan' => $pesanan->id_pesanan,
    ]);
    $this->assertDatabaseMissing('detail_pesanan', [
        'id_pesanan' => $pesanan->id_pesanan,
    ]);
    $this->assertDatabaseMissing('pembayaran', [
        'id_pesanan' => $pesanan->id_pesanan,
    ]);

    Storage::disk('public')->assertMissing('bukti-pembayaran/test.jpg');
});

test('user cannot delete other user\'s order', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $pesanan = Pesanan::create([
        'id_user' => $user1->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 25000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'selesai',
    ]);

    $response = $this->actingAs($user2)->delete(route('orders.destroy', $pesanan->id_pesanan));

    $response->assertStatus(403);

    $this->assertDatabaseHas('pesanan', [
        'id_pesanan' => $pesanan->id_pesanan,
    ]);
});

test('guests are redirected to login when trying to delete an order', function () {
    $user = User::factory()->create();

    $pesanan = Pesanan::create([
        'id_user' => $user->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 25000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'selesai',
    ]);

    $response = $this->delete(route('orders.destroy', $pesanan->id_pesanan));

    $response->assertRedirect(route('login'));

    $this->assertDatabaseHas('pesanan', [
        'id_pesanan' => $pesanan->id_pesanan,
    ]);
});

test('order history page displays delete button for each order', function () {
    $user = User::factory()->create();

    $pesanan = Pesanan::create([
        'id_user' => $user->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 25000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'selesai',
    ]);

    $response = $this->actingAs($user)->get(route('orders.index'));

    $response->assertStatus(200);
    $response->assertSee('Hapus');
    $response->assertSee('openDeleteOrderModal');
    $response->assertSee('delete-order-modal');
});

test('checkout page displays options for Ambil di Toko and Di Antar', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('checkout'));

    $response->assertStatus(200);
    $response->assertSee('Pilihan Layanan');
    $response->assertSee('Ambil di Toko');
    $response->assertSee('Di Antar');
    $response->assertSee('Lokasi / Alamat Pengantaran');
});

test('user can place order with tipe_pesanan ambil_di_toko', function () {
    $user = User::factory()->create();
    $menu = Menu::create([
        'nama_menu' => 'Americano',
        'harga' => 7000,
        'deskripsi' => 'Kopi hitam',
        'gambar' => '/images/americano.jpg',
        'kategori' => 'Kopi',
        'stok' => 10,
        'status' => 'tersedia',
    ]);

    $payload = [
        'payment_method' => 'Tunai',
        'tipe_pesanan' => 'ambil_di_toko',
        'catatan' => null,
        'cart' => [
            [
                'id' => $menu->id_menu,
                'qty' => 1,
                'price' => 7000,
            ],
        ],
    ];

    $response = $this->actingAs($user)->postJson(route('orders.store'), $payload);

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);

    $pesanan = Pesanan::latest('id_pesanan')->first();
    expect($pesanan->tipe_pesanan)->toBe('ambil_di_toko');
    expect($pesanan->alamat_pengiriman)->toBeNull();
    expect($pesanan->tipe_pesanan_label)->toBe('Ambil di Toko');
});

test('user can place order with tipe_pesanan diantar and alamat_pengiriman', function () {
    $user = User::factory()->create();
    $menu = Menu::create([
        'nama_menu' => 'Coffee Latte',
        'harga' => 10000,
        'deskripsi' => 'Latte nikmat',
        'gambar' => '/images/latte.jpg',
        'kategori' => 'Kopi',
        'stok' => 10,
        'status' => 'tersedia',
    ]);

    $payload = [
        'payment_method' => 'QRIS',
        'tipe_pesanan' => 'diantar',
        'alamat_pengiriman' => 'Gedung B Lantai 2, Ruang XII RPL 1',
        'catatan' => 'Antar pas istirahat',
        'cart' => [
            [
                'id' => $menu->id_menu,
                'qty' => 2,
                'price' => 10000,
            ],
        ],
    ];

    $response = $this->actingAs($user)->postJson(route('orders.store'), $payload);

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);

    $pesanan = Pesanan::latest('id_pesanan')->first();
    expect($pesanan->tipe_pesanan)->toBe('diantar');
    expect($pesanan->alamat_pengiriman)->toBe('Gedung B Lantai 2, Ruang XII RPL 1');
    expect($pesanan->tipe_pesanan_label)->toBe('Diantar');
    expect($pesanan->isDiantar())->toBeTrue();
});

test('storing order fails when tipe_pesanan is diantar but alamat_pengiriman is missing', function () {
    $user = User::factory()->create();
    $menu = Menu::create([
        'nama_menu' => 'Coffee Latte',
        'harga' => 10000,
        'deskripsi' => 'Latte nikmat',
        'gambar' => '/images/latte.jpg',
        'kategori' => 'Kopi',
        'stok' => 10,
        'status' => 'tersedia',
    ]);

    $payload = [
        'payment_method' => 'QRIS',
        'tipe_pesanan' => 'diantar',
        'alamat_pengiriman' => '',
        'cart' => [
            [
                'id' => $menu->id_menu,
                'qty' => 1,
                'price' => 10000,
            ],
        ],
    ];

    $response = $this->actingAs($user)->postJson(route('orders.store'), $payload);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors('alamat_pengiriman');
});

test('order tracking page displays diantar and location info when tipe_pesanan is diantar', function () {
    $user = User::factory()->create();

    $pesanan = Pesanan::create([
        'id_user' => $user->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 18000,
        'metode_pembayaran' => 'QRIS',
        'tipe_pesanan' => 'diantar',
        'alamat_pengiriman' => 'Ruang Lab Komputer 3',
        'status_pesanan' => 'siap_diambil',
    ]);

    Pembayaran::create([
        'id_pesanan' => $pesanan->id_pesanan,
        'metode' => 'QRIS',
        'nominal' => 18000,
        'tanggal_bayar' => now(),
        'status' => 'berhasil',
    ]);

    $response = $this->actingAs($user)->get(route('orders.show', $pesanan->id_pesanan));

    $response->assertStatus(200);
    $response->assertSee('Sedang Diantar');
    $response->assertSee('INFORMASI PENGANTARAN');
    $response->assertSee('Ruang Lab Komputer 3');
    $response->assertSee('Pesanan Anda sedang diantar ke lokasi Anda!');
});

test('employee order board displays diantar badge and delivery location', function () {
    $user = User::factory()->create();
    $pesanan = Pesanan::create([
        'id_user' => $user->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 20000,
        'metode_pembayaran' => 'QRIS',
        'tipe_pesanan' => 'diantar',
        'alamat_pengiriman' => 'Kelas X DKV 2',
        'status_pesanan' => 'diproses',
    ]);

    Pembayaran::create([
        'id_pesanan' => $pesanan->id_pesanan,
        'metode' => 'QRIS',
        'nominal' => 20000,
        'tanggal_bayar' => now(),
        'status' => 'berhasil',
    ]);

    $karyawan = User::factory()->create(['role' => 'karyawan']);
    $response = $this->actingAs($karyawan)->get(route('karyawan.pesanan'));

    $response->assertStatus(200);
    $response->assertSee('Diantar');
    $response->assertSee('Kelas X DKV 2');
});

test('employee struk returns tipe_pesanan and alamat_pengiriman', function () {
    $user = User::factory()->create();
    $pesanan = Pesanan::create([
        'id_user' => $user->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 20000,
        'metode_pembayaran' => 'Tunai',
        'tipe_pesanan' => 'diantar',
        'alamat_pengiriman' => 'Kelas XI TKJ 1',
        'status_pesanan' => 'selesai',
    ]);

    $karyawan = User::factory()->create(['role' => 'karyawan']);
    $response = $this->actingAs($karyawan)->getJson(route('karyawan.riwayat.struk', $pesanan->id_pesanan));

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'tipe_pesanan' => 'diantar',
        'tipe_pesanan_label' => 'Diantar',
        'alamat_pengiriman' => 'Kelas XI TKJ 1',
    ]);
});

test('user can place order with promo code and receive discount on total price', function () {
    $user = User::factory()->create();
    $menu = Menu::create([
        'nama_menu' => 'Aren Latte',
        'harga' => 8000,
        'deskripsi' => 'Kopi susu aren',
        'gambar' => '/images/aren.jpg',
        'kategori' => 'Kopi',
        'stok' => 10,
        'status' => 'tersedia',
    ]);

    Promo::create([
        'nama_promo' => 'Diskon Rp 2.000',
        'jenis_promo' => 'diskon',
        'nilai_promo' => 2000,
        'satuan_nilai' => 'rupiah',
        'kode_voucher' => 'DISKON2K',
        'is_active' => true,
    ]);

    $payload = [
        'payment_method' => 'QRIS',
        'tipe_pesanan' => 'ambil_di_toko',
        'cart' => [
            [
                'id' => $menu->id_menu,
                'qty' => 2,
                'price' => 8000,
            ],
        ],
        'promo_code' => 'DISKON2K',
    ];

    // Subtotal: 16.000, Service Fee: 2.000, Diskon: 2.000 -> Total: 16.000
    $response = $this->actingAs($user)->postJson(route('orders.store'), $payload);

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);

    $pesanan = Pesanan::latest('id_pesanan')->first();
    expect((int) $pesanan->total_harga)->toBe(16000);
});
