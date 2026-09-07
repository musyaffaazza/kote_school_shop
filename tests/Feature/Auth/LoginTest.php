<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
    $response->assertSee('Masuk ke Akun Anda');
    $response->assertSee('Remember me');
});

test('admin can login successfully with remember me and is redirected to admin dashboard', function () {
    $admin = User::factory()->create([
        'email' => 'admin@koteshop.com',
        'password' => 'admin12345',
        'role' => 'admin',
    ]);

    $response = $this->post('/login', [
        'email' => 'admin@koteshop.com',
        'password' => 'admin12345',
        'remember' => '1',
    ]);

    $response->assertRedirect(route('admin.dashboard'));
    $this->assertAuthenticatedAs($admin);

    $admin->refresh();
    expect($admin->remember_token)->not->toBeNull();
});

test('karyawan can login successfully with remember me and is redirected to karyawan dashboard', function () {
    $karyawan = User::factory()->create([
        'email' => 'karyawan@koteshop.com',
        'password' => 'karyawan123',
        'role' => 'karyawan',
    ]);

    $response = $this->post('/login', [
        'email' => 'karyawan@koteshop.com',
        'password' => 'karyawan123',
        'remember' => 'on',
    ]);

    $response->assertRedirect(route('karyawan.dashboard'));
    $this->assertAuthenticatedAs($karyawan);
});

test('users cannot authenticate with invalid password', function () {
    $user = User::factory()->create([
        'email' => 'user@example.com',
        'password' => 'correct-password',
    ]);

    $response = $this->post('/login', [
        'email' => 'user@example.com',
        'password' => 'wrong-password',
        'remember' => '1',
    ]);

    $this->assertGuest();
    $response->assertSessionHasErrors('email');
});
