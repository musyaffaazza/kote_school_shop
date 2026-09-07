<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected to login when trying to access admin dashboard', function () {
    $response = $this->get(route('admin.dashboard'));

    $response->assertRedirect(route('login'));
});

test('pelanggan cannot access admin dashboard', function () {
    $user = User::factory()->create([
        'role' => 'pelanggan',
    ]);

    $response = $this->actingAs($user)->get(route('admin.dashboard'));

    $response->assertStatus(403);
});

test('karyawan cannot access admin dashboard', function () {
    $user = User::factory()->create([
        'role' => 'karyawan',
    ]);

    $response = $this->actingAs($user)->get(route('admin.dashboard'));

    $response->assertStatus(403);
});

test('admin can access admin dashboard successfully', function () {
    $user = User::factory()->create([
        'role' => 'admin',
    ]);

    $response = $this->actingAs($user)->get(route('admin.dashboard'));

    $response->assertStatus(200);
    $response->assertSee('Admin Dashboard');
    $response->assertSee('Total Penjualan');
    $response->assertSee('Total Pesanan');
    $response->assertSee('Pendapatan Hari Ini');
    $response->assertSee('Halaman User');
    $response->assertSee(route('home'));
});
