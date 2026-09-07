<?php

use App\Models\Menu;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('halaman menu dapat diakses dan menampilkan daftar menu', function () {
    // Ambil data menu yang sudah ada, atau buat jika kosong
    $menu = Menu::first();

    if (! $menu) {
        $menu = Menu::create([
            'nama_menu' => 'Aren Latte',
            'kategori' => 'Coffee',
            'harga' => 8000,
            'stok' => 10,
            'deskripsi' => 'Espresso dengan susu dan gula aren alami.',
            'gambar' => '/images/ArenLatte/aren_latte.jpg',
            'status' => 'tersedia',
        ]);
    }

    $response = $this->get(route('menu'));

    $response->assertStatus(200);
    $response->assertSee('Menu');
    $response->assertSee($menu->nama_menu);
    $response->assertSee('Rp '.number_format($menu->harga, 0, ',', '.'));
});
