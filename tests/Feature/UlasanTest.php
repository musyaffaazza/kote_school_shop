<?php

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Ulasan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('guests are redirected to login when accessing ulasan page', function () {
    $response = $this->get(route('ulasan.create'));

    $response->assertRedirect(route('login'));
});

test('logged in user can view ulasan form with menu chips and star rating', function () {
    $user = User::factory()->create();

    $menu1 = Menu::create([
        'nama_menu' => 'Aren Latte',
        'harga' => 8000,
        'deskripsi' => 'Kopi aren',
        'gambar' => '/images/ArenLatte/aren_latte.jpg',
        'kategori' => 'Kopi',
        'stok' => 10,
        'status' => 'tersedia',
    ]);

    $menu2 = Menu::create([
        'nama_menu' => 'Americano',
        'harga' => 7000,
        'deskripsi' => 'Kopi hitam',
        'gambar' => '/images/Americano/americano.jpg',
        'kategori' => 'Kopi',
        'stok' => 10,
        'status' => 'tersedia',
    ]);

    $pesanan = Pesanan::create([
        'id_user' => $user->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 17000,
        'metode_pembayaran' => 'QRIS',
        'status_pesanan' => 'selesai',
    ]);

    DB::table('detail_pesanan')->insert([
        [
            'id_pesanan' => $pesanan->id_pesanan,
            'id_menu' => $menu1->id_menu,
            'jumlah' => 1,
            'opsi' => null,
            'catatan' => null,
            'harga' => 8000,
            'subtotal' => 8000,
        ],
        [
            'id_pesanan' => $pesanan->id_pesanan,
            'id_menu' => $menu2->id_menu,
            'jumlah' => 1,
            'opsi' => null,
            'catatan' => null,
            'harga' => 7000,
            'subtotal' => 7000,
        ],
    ]);

    $response = $this->actingAs($user)->get(route('ulasan.create', $pesanan->id_pesanan));

    $response->assertStatus(200);
    $response->assertSee('Berikan Ulasanmu');
    $response->assertSee('Bagikan pengalamanmu');
    $response->assertSee('PENILAIAN KESELURUHAN');
    $response->assertSee('Menu yang diulas');
    $response->assertSee('Aren Latte');
    $response->assertSee('Americano');
    $response->assertSee('KIRIM ULASAN');
});

test('user can submit ulasan and it is saved to database', function () {
    $user = User::factory()->create();

    $menu = Menu::create([
        'nama_menu' => 'Aren Latte',
        'harga' => 8000,
        'deskripsi' => 'Kopi aren',
        'gambar' => '/images/ArenLatte/aren_latte.jpg',
        'kategori' => 'Kopi',
        'stok' => 10,
        'status' => 'tersedia',
    ]);

    $pesanan = Pesanan::create([
        'id_user' => $user->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 10000,
        'metode_pembayaran' => 'Tunai',
        'status_pesanan' => 'selesai',
    ]);

    DB::table('detail_pesanan')->insert([
        'id_pesanan' => $pesanan->id_pesanan,
        'id_menu' => $menu->id_menu,
        'jumlah' => 1,
        'opsi' => null,
        'catatan' => null,
        'harga' => 8000,
        'subtotal' => 8000,
    ]);

    $response = $this->actingAs($user)->post(route('ulasan.store', $pesanan->id_pesanan), [
        'rating' => 5,
        'komentar' => 'Kopi sangat nikmat dan pelayanannya cepat!',
        'menu_ids' => [$menu->id_menu],
    ]);

    $response->assertRedirect(route('home').'#ulasan');
    $response->assertSessionHas('success');

    $ulasan = Ulasan::where('id_user', $user->id_user)->first();
    expect($ulasan)->not->toBeNull();
    expect($ulasan->rating)->toBe(5);
    expect($ulasan->komentar)->toBe('Kopi sangat nikmat dan pelayanannya cepat!');
    expect($ulasan->id_menu)->toBe($menu->id_menu);
    expect($ulasan->status)->toBe('aktif');
});

test('homepage shows empty state when no ulasan exist', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('Apa Kata Mereka?');
    $response->assertSee('0 Ulasan Total');
    $response->assertSee('Belum Ada Ulasan');
});

test('submitted ulasan is displayed on homepage Apa Kata Mereka section', function () {
    $user = User::factory()->create(['nama' => 'Rian Hidayat']);
    $menu = Menu::create([
        'nama_menu' => 'Caramel Macchiato',
        'harga' => 15000,
        'deskripsi' => 'Kopi caramel lezat',
        'gambar' => '/images/caramel.jpg',
        'kategori' => 'Kopi',
        'stok' => 10,
        'status' => 'tersedia',
    ]);

    Ulasan::create([
        'id_user' => $user->id_user,
        'id_menu' => $menu->id_menu,
        'rating' => 5,
        'komentar' => 'Rasanya sangat mantap dan pas di lidah!',
        'tanggal_ulasan' => now(),
        'status' => 'aktif',
    ]);

    $response = $this->get(route('home'));

    $response->assertStatus(200);
    $response->assertSee('Apa Kata Mereka?');
    $response->assertSee('Rian Hidayat');
    $response->assertSee('Caramel Macchiato');
    $response->assertSee('Rasanya sangat mantap dan pas di lidah!');
    $response->assertSee('1 Ulasan Total');
    $response->assertDontSee('Belum Ada Ulasan');
});

test('ulasan submission validates rating and komentar', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('ulasan.store'), [
        'rating' => '',
        'komentar' => '',
    ]);

    $response->assertSessionHasErrors(['rating', 'komentar']);
});

test('user cannot access ulasan form of another user order', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $pesanan = Pesanan::create([
        'id_user' => $user1->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 10000,
        'metode_pembayaran' => 'Tunai',
        'status_pesanan' => 'selesai',
    ]);

    $response = $this->actingAs($user2)->get(route('ulasan.create', $pesanan->id_pesanan));

    $response->assertStatus(403);
});

test('user cannot submit ulasan for another user order', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $pesanan = Pesanan::create([
        'id_user' => $user1->id_user,
        'tanggal_pesan' => now(),
        'total_harga' => 10000,
        'metode_pembayaran' => 'Tunai',
        'status_pesanan' => 'selesai',
    ]);

    $response = $this->actingAs($user2)->post(route('ulasan.store', $pesanan->id_pesanan), [
        'rating' => 5,
        'komentar' => 'Mencoba submit review milik orang lain',
    ]);

    $response->assertStatus(403);
});
