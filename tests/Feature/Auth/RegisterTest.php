<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
    $response->assertSee('Buat Akun Baru');
    $response->assertSee('NIS');
});

test('terms acceptance is required for registration', function () {
    $response = $this->post('/register', [
        'nama' => 'John Doe',
        'email' => 'john@example.com',
        'no_hp' => '08123456789',
        'nis' => '1234567890123456',
        'kelas' => 'XI RPL 1',
        'jenis_kelamin' => 'L',
        'alamat' => 'Jl. Contoh No.1',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertSessionHasErrors('terms');
});

test('user can register successfully and data is saved to database', function () {
    $response = $this->post('/register', [
        'nama' => 'Budi Santoso',
        'email' => 'budi@example.com',
        'no_hp' => '081234567890',
        'nis' => '1234567890123456',
        'kelas' => 'XII RPL 1',
        'jenis_kelamin' => 'L',
        'alamat' => 'Jl. Bogor No.5',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'terms' => 'on',
    ]);

    $response->assertRedirect('/');

    $this->assertDatabaseHas('users', [
        'email' => 'budi@example.com',
        'nama' => 'Budi Santoso',
        'no_hp' => '081234567890',
        'nis' => '1234567890123456',
        'kelas' => 'XII RPL 1',
        'jenis_kelamin' => 'L',
        'role' => 'pelanggan',
        'alamat' => 'Jl. Bogor No.5',
    ]);

    $this->assertAuthenticated();
});

test('user can upload identity photo during registration', function () {
    Storage::fake('public');

    $file = UploadedFile::fake()->create('ktp_test.jpg', 100, 'image/jpeg');

    $response = $this->post('/register', [
        'nama' => 'Siti Aminah',
        'email' => 'siti.aminah@example.com',
        'no_hp' => '081299998888',
        'nis' => '9998887776665554',
        'kelas' => 'X AK 1',
        'jenis_kelamin' => 'P',
        'foto_identitas' => $file,
        'alamat' => 'Jl. Jakarta No.20',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'terms' => 'on',
    ]);

    $response->assertRedirect('/');

    $user = User::where('email', 'siti.aminah@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->foto_identitas)->not->toBeNull();
    Storage::disk('public')->assertExists($user->foto_identitas);
});
