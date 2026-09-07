<?php

use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('guests are redirected to login when trying to access admin menu pages', function () {
    $menu = Menu::create([
        'nama_menu' => 'Espresso',
        'kategori' => 'Coffee',
        'harga' => 12000,
        'stok' => 10,
        'deskripsi' => 'Kopi hitam murni',
        'gambar' => '/images/espresso.jpg',
        'status' => 'tersedia',
    ]);

    $this->get(route('admin.menu.index'))->assertRedirect(route('login'));
    $this->get(route('admin.menu.create'))->assertRedirect(route('login'));
    $this->get(route('admin.menu.edit', $menu))->assertRedirect(route('login'));
});

test('pelanggan cannot access admin menu pages', function () {
    $user = User::factory()->create(['role' => 'pelanggan']);
    $menu = Menu::create([
        'nama_menu' => 'Espresso',
        'kategori' => 'Coffee',
        'harga' => 12000,
        'stok' => 10,
        'deskripsi' => 'Kopi hitam murni',
        'gambar' => '/images/espresso.jpg',
        'status' => 'tersedia',
    ]);

    $this->actingAs($user)->get(route('admin.menu.index'))->assertStatus(403);
    $this->actingAs($user)->get(route('admin.menu.create'))->assertStatus(403);
    $this->actingAs($user)->get(route('admin.menu.edit', $menu))->assertStatus(403);
});

test('admin can access admin menu index page and view menus list', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $menu = Menu::create([
        'nama_menu' => 'Cappuccino',
        'kategori' => 'Coffee',
        'harga' => 15000,
        'stok' => 20,
        'deskripsi' => 'Espresso dengan foam susu',
        'gambar' => '/images/cappuccino.jpg',
        'status' => 'tersedia',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.menu.index'));

    $response->assertStatus(200);
    $response->assertSee('Cappuccino');
    $response->assertSee('Coffee');
    $response->assertSee('Rp 15.000');
});

test('admin can search menus', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    Menu::create([
        'nama_menu' => 'Cappuccino',
        'kategori' => 'Coffee',
        'harga' => 15000,
        'stok' => 20,
        'status' => 'tersedia',
    ]);
    Menu::create([
        'nama_menu' => 'Green Tea Latte',
        'kategori' => 'Non Coffee',
        'harga' => 18000,
        'stok' => 15,
        'status' => 'tersedia',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.menu.index', ['search' => 'Cappuccino']));
    $response->assertSee('Cappuccino');
    $response->assertDontSee('Green Tea Latte');
});

test('admin can filter menus by category', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    Menu::create([
        'nama_menu' => 'Cappuccino',
        'kategori' => 'Coffee',
        'harga' => 15000,
        'stok' => 20,
        'status' => 'tersedia',
    ]);
    Menu::create([
        'nama_menu' => 'Green Tea Latte',
        'kategori' => 'Non Coffee',
        'harga' => 18000,
        'stok' => 15,
        'status' => 'tersedia',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.menu.index', ['kategori' => 'Non Coffee']));
    $response->assertSee('Green Tea Latte');
    $response->assertDontSee('Cappuccino');
});

test('admin can view create menu page', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('admin.menu.create'));

    $response->assertStatus(200);
    $response->assertSee('Tambah Menu Baru');
});

test('admin can store a new menu with photo and sub-photos', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role' => 'admin']);
    $file = UploadedFile::fake()->create('matcha.jpg', 100, 'image/jpeg');
    $sub1 = UploadedFile::fake()->create('sub1.jpg', 50, 'image/jpeg');
    $sub2 = UploadedFile::fake()->create('sub2.jpg', 50, 'image/jpeg');
    $sub3 = UploadedFile::fake()->create('sub3.jpg', 50, 'image/jpeg');

    $response = $this->actingAs($admin)->post(route('admin.menu.store'), [
        'nama_menu' => 'Matcha Frappe',
        'kategori' => 'Non Coffee',
        'harga' => 20000,
        'stok' => 15,
        'deskripsi' => 'Minuman matcha dingin yang blend',
        'gambar' => $file,
        'gambar_sub_1' => $sub1,
        'gambar_sub_2' => $sub2,
        'gambar_sub_3' => $sub3,
        'status' => 'tersedia',
    ]);

    $response->assertRedirect(route('admin.menu.index'));
    $this->assertDatabaseHas('menu', [
        'nama_menu' => 'Matcha Frappe',
        'kategori' => 'Non Coffee',
        'harga' => 20000,
        'stok' => 15,
        'status' => 'tersedia',
    ]);

    $menu = Menu::where('nama_menu', 'Matcha Frappe')->first();
    $imagePath = str_replace('/storage/', '', $menu->gambar);
    Storage::disk('public')->assertExists($imagePath);
    Storage::disk('public')->assertExists('menu/'.$menu->id_menu.'/sub_1.jpg');
    Storage::disk('public')->assertExists('menu/'.$menu->id_menu.'/sub_2.jpg');
    Storage::disk('public')->assertExists('menu/'.$menu->id_menu.'/sub_3.jpg');
});

test('admin can edit an existing menu', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $menu = Menu::create([
        'nama_menu' => 'Espresso',
        'kategori' => 'Coffee',
        'harga' => 12000,
        'stok' => 10,
        'status' => 'tersedia',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.menu.edit', $menu));

    $response->assertStatus(200);
    $response->assertSee('Edit Menu');
    $response->assertSee('Espresso');
});

test('admin can update a menu and change photo', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role' => 'admin']);
    $menu = Menu::create([
        'nama_menu' => 'Espresso',
        'kategori' => 'Coffee',
        'harga' => 12000,
        'stok' => 10,
        'gambar' => '/storage/menu/old.jpg',
        'status' => 'tersedia',
    ]);

    $file = UploadedFile::fake()->create('espresso_new.jpg', 100, 'image/jpeg');
    $sub1 = UploadedFile::fake()->create('sub1_new.jpg', 50, 'image/jpeg');

    $response = $this->actingAs($admin)->put(route('admin.menu.update', $menu), [
        'nama_menu' => 'Espresso Solo',
        'kategori' => 'Coffee',
        'harga' => 13000,
        'stok' => 5,
        'gambar' => $file,
        'gambar_sub_1' => $sub1,
        'status' => 'habis',
    ]);

    $response->assertRedirect(route('admin.menu.index'));
    $this->assertDatabaseHas('menu', [
        'id_menu' => $menu->id_menu,
        'nama_menu' => 'Espresso Solo',
        'harga' => 13000,
        'stok' => 5,
        'status' => 'habis',
    ]);

    $menu->refresh();
    $imagePath = str_replace('/storage/', '', $menu->gambar);
    Storage::disk('public')->assertExists($imagePath);
    Storage::disk('public')->assertExists('menu/'.$menu->id_menu.'/sub_1.jpg');
});

test('admin can delete a menu', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role' => 'admin']);
    $menu = Menu::create([
        'nama_menu' => 'Espresso To Delete',
        'kategori' => 'Coffee',
        'harga' => 12000,
        'stok' => 10,
        'gambar' => '/storage/menu/todelete.jpg',
        'status' => 'tersedia',
    ]);

    $response = $this->actingAs($admin)->delete(route('admin.menu.destroy', $menu));

    $response->assertRedirect(route('admin.menu.index'));
    $this->assertDatabaseMissing('menu', [
        'id_menu' => $menu->id_menu,
    ]);
});
