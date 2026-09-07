<?php

use App\Models\Menu;
use App\Models\Ulasan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected when trying to access admin review management', function () {
    $response = $this->get(route('admin.ulasan.index'));

    $response->assertRedirect(route('login'));
});

test('regular customers cannot access admin review management', function () {
    $user = User::factory()->create(['role' => 'pelanggan']);

    $response = $this->actingAs($user)->get(route('admin.ulasan.index'));

    $response->assertStatus(403);
});

test('admin can access review management and see summary metrics', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user = User::factory()->create(['nama' => 'Syifanny']);
    $menu = Menu::create([
        'nama_menu' => 'Aren Latte',
        'harga' => 8000,
        'deskripsi' => 'Kopi susu gula aren',
        'gambar' => '/images/aren.jpg',
        'kategori' => 'Kopi',
        'stok' => 10,
        'status' => 'tersedia',
    ]);

    Ulasan::create([
        'id_user' => $user->id_user,
        'id_menu' => $menu->id_menu,
        'rating' => 5,
        'komentar' => 'Kopi terbaik di sekolah!',
        'tanggal_ulasan' => now(),
        'status' => 'aktif',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.ulasan.index'));

    $response->assertStatus(200);
    $response->assertSee('Manajemen Ulasan');
    $response->assertSee('Total Ulasan');
    $response->assertSee('Rating Rata-Rata');
    $response->assertSee('Ulasan Positif');
    $response->assertSee('Syifanny');
    $response->assertSee('Aren Latte');
    $response->assertSee('Kopi terbaik di sekolah!');
});

test('admin can filter ulasan by sort terbaru, terlama, tertinggi, and terendah', function () {
    $admin = User::factory()->create(['role' => 'admin']);
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

    $review1 = Ulasan::create([
        'id_user' => $user->id_user,
        'id_menu' => $menu->id_menu,
        'rating' => 1,
        'komentar' => 'Terlalu pahit bagi saya',
        'tanggal_ulasan' => now()->subDays(5),
        'status' => 'aktif',
    ]);

    $review2 = Ulasan::create([
        'id_user' => $user->id_user,
        'id_menu' => $menu->id_menu,
        'rating' => 5,
        'komentar' => 'Sangat mantap dan segar!',
        'tanggal_ulasan' => now()->subDay(),
        'status' => 'aktif',
    ]);

    // Test sort tertinggi
    $responseHighest = $this->actingAs($admin)->get(route('admin.ulasan.index', ['sort' => 'tertinggi']));
    $responseHighest->assertStatus(200);
    $responseHighest->assertSeeInOrder(['Sangat mantap dan segar!', 'Terlalu pahit bagi saya']);

    // Test sort terendah
    $responseLowest = $this->actingAs($admin)->get(route('admin.ulasan.index', ['sort' => 'terendah']));
    $responseLowest->assertStatus(200);
    $responseLowest->assertSeeInOrder(['Terlalu pahit bagi saya', 'Sangat mantap dan segar!']);

    // Test sort terlama
    $responseOldest = $this->actingAs($admin)->get(route('admin.ulasan.index', ['sort' => 'terlama']));
    $responseOldest->assertStatus(200);
    $responseOldest->assertSeeInOrder(['Terlalu pahit bagi saya', 'Sangat mantap dan segar!']);
});

test('admin can filter ulasan by rating and search keyword', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $user1 = User::factory()->create(['nama' => 'Budi Santoso']);
    $user2 = User::factory()->create(['nama' => 'Siti Aminah']);
    $menu = Menu::create([
        'nama_menu' => 'Matcha Latte',
        'harga' => 12000,
        'deskripsi' => 'Matcha nikmat',
        'gambar' => '/images/matcha.jpg',
        'kategori' => 'Non-Kopi',
        'stok' => 10,
        'status' => 'tersedia',
    ]);

    Ulasan::create([
        'id_user' => $user1->id_user,
        'id_menu' => $menu->id_menu,
        'rating' => 5,
        'komentar' => 'Matcha terenak!',
        'tanggal_ulasan' => now(),
        'status' => 'aktif',
    ]);

    Ulasan::create([
        'id_user' => $user2->id_user,
        'id_menu' => $menu->id_menu,
        'rating' => 2,
        'komentar' => 'Agak terlalu manis',
        'tanggal_ulasan' => now(),
        'status' => 'aktif',
    ]);

    // Search by name
    $searchResponse = $this->actingAs($admin)->get(route('admin.ulasan.index', ['search' => 'Budi']));
    $searchResponse->assertStatus(200);
    $searchResponse->assertSee('Budi Santoso');
    $searchResponse->assertDontSee('Siti Aminah');

    // Filter by rating 2
    $ratingResponse = $this->actingAs($admin)->get(route('admin.ulasan.index', ['rating' => '2']));
    $ratingResponse->assertStatus(200);
    $ratingResponse->assertSee('Agak terlalu manis');
    $ratingResponse->assertDontSee('Matcha terenak!');
});

test('admin can reply to a customer review', function () {
    $admin = User::factory()->create(['role' => 'admin']);
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

    $ulasan = Ulasan::create([
        'id_user' => $user->id_user,
        'id_menu' => $menu->id_menu,
        'rating' => 5,
        'komentar' => 'Kopi mantap!',
        'tanggal_ulasan' => now(),
        'status' => 'aktif',
    ]);

    $response = $this->actingAs($admin)->post(route('admin.ulasan.reply', $ulasan->id_ulasan), [
        'balasan' => 'Terima kasih banyak sudah mampir ke Kote Coffee!',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Balasan ulasan berhasil disimpan.');

    $ulasan->refresh();
    expect($ulasan->balasan)->toBe('Terima kasih banyak sudah mampir ke Kote Coffee!');
    expect($ulasan->tanggal_balasan)->not->toBeNull();
    expect($ulasan->hasBalasan())->toBeTrue();
});

test('admin can delete reply from a review', function () {
    $admin = User::factory()->create(['role' => 'admin']);
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

    $ulasan = Ulasan::create([
        'id_user' => $user->id_user,
        'id_menu' => $menu->id_menu,
        'rating' => 5,
        'komentar' => 'Kopi mantap!',
        'balasan' => 'Balasan lama',
        'tanggal_balasan' => now(),
        'tanggal_ulasan' => now(),
        'status' => 'aktif',
    ]);

    $response = $this->actingAs($admin)->delete(route('admin.ulasan.reply.destroy', $ulasan->id_ulasan));

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Balasan ulasan berhasil dihapus.');

    $ulasan->refresh();
    expect($ulasan->balasan)->toBeNull();
    expect($ulasan->tanggal_balasan)->toBeNull();
});

test('admin can delete a customer review and it is removed from database and dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);
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

    $ulasan = Ulasan::create([
        'id_user' => $user->id_user,
        'id_menu' => $menu->id_menu,
        'rating' => 5,
        'komentar' => 'Ulasan yang akan dihapus',
        'tanggal_ulasan' => now(),
        'status' => 'aktif',
    ]);

    $response = $this->actingAs($admin)->delete(route('admin.ulasan.destroy', $ulasan->id_ulasan));

    $response->assertRedirect();
    $response->assertSessionHas('success', 'Ulasan pelanggan berhasil dihapus.');

    $this->assertDatabaseMissing('ulasan', [
        'id_ulasan' => $ulasan->id_ulasan,
    ]);
});
