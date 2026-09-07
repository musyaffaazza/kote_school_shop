<?php

use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('halaman detail menu dapat diakses dan menampilkan informasi menu', function () {
    $menu = Menu::create([
        'nama_menu' => 'Aren Latte',
        'kategori' => 'Coffee',
        'harga' => 8000,
        'stok' => 10,
        'deskripsi' => 'Espresso dengan susu dan gula aren alami.',
        'gambar' => '/images/ArenLatte/aren_latte.jpg',
        'status' => 'tersedia',
    ]);

    $response = $this->get(route('menu.show', $menu));

    $response->assertStatus(200);
    $response->assertSee('Aren Latte');
    $response->assertSee('Rp 8.000');
    $response->assertSee('Coffee');
    $response->assertSee('Level Gula');
    $response->assertSee('Level Es');
    $response->assertSee('Toppings');
    $response->assertSee('Tambah ke Keranjang');
});
