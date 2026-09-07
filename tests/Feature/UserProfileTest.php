<?php

use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('guests are redirected to login when trying to access profile page', function () {
    $response = $this->get(route('profile'));

    $response->assertRedirect(route('login'));
});

test('logged in user can render profile page and see their details', function () {
    $user = User::factory()->create([
        'nama' => 'Azza Musyaffa',
        'email' => 'AzzaMsfa@gmail.com',
        'no_hp' => '+62 821-1377-8035',
    ]);

    $response = $this->actingAs($user)->get(route('profile'));

    $response->assertStatus(200);
    $response->assertSee('Azza Musyaffa');
    $response->assertSee('AzzaMsfa@gmail.com');
    $response->assertSee('+62 821-1377-8035');
    $response->assertSee('Menu Favorit');
});

test('logged in user can render profile page with favorite menus displayed', function () {
    $user = User::factory()->create();

    Menu::create([
        'nama_menu' => 'Aren Latte',
        'kategori' => 'Kopi',
        'harga' => 18000,
        'stok' => 20,
        'deskripsi' => 'Espresso dengan susu segar dan gula aren asli.',
        'gambar' => 'storage/menu/aren_latte.jpg',
        'status' => 'tersedia',
    ]);

    $response = $this->actingAs($user)->get(route('profile'));

    $response->assertStatus(200);
    $response->assertSee('Aren Latte');
    $response->assertSee('Rp 18k');
});

test('logged in user can update their profile information', function () {
    $user = User::factory()->create([
        'nama' => 'Old Name',
        'email' => 'old@example.com',
        'no_hp' => '081234567890',
    ]);

    $response = $this->actingAs($user)->post(route('profile.update'), [
        'nama' => 'Azza Musyaffa',
        'email' => 'AzzaMsfa@gmail.com',
        'no_hp' => '+62 821-1377-8035',
    ]);

    $response->assertRedirect(route('profile'));
    $response->assertSessionHas('success', 'Profil Anda berhasil diperbarui.');

    $this->assertDatabaseHas('users', [
        'id_user' => $user->id_user,
        'nama' => 'Azza Musyaffa',
        'email' => 'AzzaMsfa@gmail.com',
        'no_hp' => '+62 821-1377-8035',
    ]);
});

test('logged in user can update their password successfully', function () {
    $user = User::factory()->create([
        'password' => Hash::make('oldpassword123'),
    ]);

    $response = $this->actingAs($user)->post(route('profile.password'), [
        'password_lama' => 'oldpassword123',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertRedirect(route('profile'));
    $response->assertSessionHas('success', 'Password Anda berhasil diperbarui.');

    $user->refresh();
    expect(Hash::check('newpassword123', $user->password))->toBeTrue();
});

test('logged in user cannot update their password with incorrect old password', function () {
    $user = User::factory()->create([
        'password' => Hash::make('oldpassword123'),
    ]);

    $response = $this->actingAs($user)->post(route('profile.password'), [
        'password_lama' => 'wrongpassword',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $response->assertSessionHasErrors('password_lama');

    $user->refresh();
    expect(Hash::check('oldpassword123', $user->password))->toBeTrue();
});
