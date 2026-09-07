<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('authenticated user can logout and is redirected to home', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect(route('home'));
    $response->assertSessionHas('success', 'Anda telah berhasil keluar.');
    $this->assertGuest();
});

test('admin can logout successfully', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $response = $this->actingAs($admin)->post(route('logout'));

    $response->assertRedirect(route('home'));
    $this->assertGuest();
});

test('karyawan can logout successfully', function () {
    $karyawan = User::factory()->create([
        'role' => 'karyawan',
    ]);

    $response = $this->actingAs($karyawan)->post(route('logout'));

    $response->assertRedirect(route('home'));
    $this->assertGuest();
});

test('unauthenticated user cannot logout', function () {
    $response = $this->post(route('logout'));

    $response->assertRedirect(route('login'));
});

test('logout confirmation modal is present on authenticated pages', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertSuccessful();
    $response->assertSee('id="logout-modal"', false);
    $response->assertSee('Konfirmasi Logout');
    $response->assertSee('openLogoutModal()', false);
});

test('logout confirmation modal is present on admin dashboard', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertSuccessful();
    $response->assertSee('id="logout-modal"', false);
    $response->assertSee('Konfirmasi Logout');
    $response->assertSee('openLogoutModal()', false);
});

test('logout confirmation modal is present on karyawan dashboard', function () {
    $karyawan = User::factory()->create([
        'role' => 'karyawan',
    ]);

    $response = $this->actingAs($karyawan)->get(route('karyawan.dashboard'));

    $response->assertSuccessful();
    $response->assertSee('id="logout-modal"', false);
    $response->assertSee('Konfirmasi Logout');
    $response->assertSee('openLogoutModal()', false);
});
