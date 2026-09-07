<?php

use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('guests are redirected to login when trying to access karyawan menu management', function () {
    $response = $this->get(route('karyawan.menu'));

    $response->assertRedirect(route('login'));
});

test('pelanggan cannot access karyawan menu management', function () {
    $user = User::factory()->create([
        'role' => 'pelanggan',
    ]);

    $response = $this->actingAs($user)->get(route('karyawan.menu'));

    $response->assertStatus(403);
});

test('karyawan can access karyawan menu management successfully', function () {
    $user = User::factory()->create([
        'role' => 'karyawan',
    ]);

    $response = $this->actingAs($user)->get(route('karyawan.menu'));

    $response->assertStatus(200);
    $response->assertSee('Manajemen Menu');
});

test('admin can access karyawan menu management successfully', function () {
    $user = User::factory()->create([
        'role' => 'admin',
    ]);

    $response = $this->actingAs($user)->get(route('karyawan.menu'));

    $response->assertStatus(200);
    $response->assertSee('Manajemen Menu');
});

test('karyawan can search and filter menus', function () {
    $user = User::factory()->create([
        'role' => 'karyawan',
    ]);

    // Insert mock menus directly using DB to match app convention (no factories for Menu in typical Laravel setup or custom ID structure)
    DB::table('menu')->insert([
        [
            'nama_menu' => 'Coffee Latte',
            'kategori' => 'kopi',
            'harga' => 10000.00,
            'stok' => 15,
            'status' => 'tersedia',
        ],
        [
            'nama_menu' => 'Choco Lava',
            'kategori' => 'snack',
            'harga' => 12000.00,
            'stok' => 5,
            'status' => 'tersedia',
        ],
    ]);

    // Test search
    $response = $this->actingAs($user)->get(route('karyawan.menu', ['search' => 'Coffee']));
    $response->assertSee('Coffee Latte');
    $response->assertDontSee('Choco Lava');

    // Test category filter
    $response = $this->actingAs($user)->get(route('karyawan.menu', ['kategori' => 'snack']));
    $response->assertSee('Choco Lava');
    $response->assertDontSee('Coffee Latte');
});

test('karyawan can update stock and status via AJAX', function () {
    $user = User::factory()->create([
        'role' => 'karyawan',
    ]);

    $menuId = DB::table('menu')->insertGetId([
        'nama_menu' => 'Americano',
        'kategori' => 'kopi',
        'harga' => 8000.00,
        'stok' => 10,
        'status' => 'tersedia',
    ]);

    $menu = Menu::find($menuId);

    $response = $this->actingAs($user)->postJson(route('karyawan.menu.update-stock', $menu), [
        'stok' => 20,
        'status' => 'tersedia',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'stok' => 20,
        'status' => 'tersedia',
    ]);

    $this->assertDatabaseHas('menu', [
        'id_menu' => $menuId,
        'stok' => 20,
        'status' => 'tersedia',
    ]);
});

test('stock set to 0 automatically changes status to habis', function () {
    $user = User::factory()->create([
        'role' => 'karyawan',
    ]);

    $menuId = DB::table('menu')->insertGetId([
        'nama_menu' => 'Matcha Latte',
        'kategori' => 'kopi',
        'harga' => 12000.00,
        'stok' => 5,
        'status' => 'tersedia',
    ]);

    $menu = Menu::find($menuId);

    // Update stock to 0 but submit 'tersedia' status
    $response = $this->actingAs($user)->postJson(route('karyawan.menu.update-stock', $menu), [
        'stok' => 0,
        'status' => 'tersedia',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'stok' => 0,
        'status' => 'habis', // Status should be forced to 'habis' by the backend
    ]);

    $this->assertDatabaseHas('menu', [
        'id_menu' => $menuId,
        'stok' => 0,
        'status' => 'habis',
    ]);
});

test('stock set to greater than 0 with status tersedia is saved successfully', function () {
    $user = User::factory()->create([
        'role' => 'karyawan',
    ]);

    $menuId = DB::table('menu')->insertGetId([
        'nama_menu' => 'Espresso',
        'kategori' => 'kopi',
        'harga' => 7000.00,
        'stok' => 0,
        'status' => 'habis',
    ]);

    $menu = Menu::find($menuId);

    // Update stock from 0 to 5, status to 'tersedia' (which corresponds to how frontend UI behaves now)
    $response = $this->actingAs($user)->postJson(route('karyawan.menu.update-stock', $menu), [
        'stok' => 5,
        'status' => 'tersedia',
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'stok' => 5,
        'status' => 'tersedia',
    ]);

    $this->assertDatabaseHas('menu', [
        'id_menu' => $menuId,
        'stok' => 5,
        'status' => 'tersedia',
    ]);
});
