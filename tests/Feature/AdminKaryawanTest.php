<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('guests are redirected to login when trying to access admin karyawan pages', function () {
    $karyawan = User::factory()->create([
        'role' => 'karyawan',
        'jabatan' => 'Barista',
    ]);

    $this->get(route('admin.karyawan.index'))->assertRedirect(route('login'));
    $this->get(route('admin.karyawan.create'))->assertRedirect(route('login'));
    $this->get(route('admin.karyawan.edit', $karyawan))->assertRedirect(route('login'));
});

test('pelanggan cannot access admin karyawan pages', function () {
    $user = User::factory()->create(['role' => 'pelanggan']);
    $karyawan = User::factory()->create([
        'role' => 'karyawan',
        'jabatan' => 'Barista',
    ]);

    $this->actingAs($user)->get(route('admin.karyawan.index'))->assertStatus(403);
    $this->actingAs($user)->get(route('admin.karyawan.create'))->assertStatus(403);
    $this->actingAs($user)->get(route('admin.karyawan.edit', $karyawan))->assertStatus(403);
});

test('admin can access admin karyawan index page and view karyawan list', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create([
        'nama' => 'Budi Santoso',
        'email' => 'budi@kote.com',
        'role' => 'karyawan',
        'jabatan' => 'Barista',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.karyawan.index'));

    $response->assertStatus(200);
    $response->assertSee('Budi Santoso');
    $response->assertSee('budi@kote.com');
    $response->assertSee('Barista');
});

test('admin can search karyawan', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create([
        'nama' => 'Budi Santoso',
        'email' => 'budi@kote.com',
        'role' => 'karyawan',
        'jabatan' => 'Barista',
    ]);
    User::factory()->create([
        'nama' => 'Siti Aminah',
        'email' => 'siti@kote.com',
        'role' => 'karyawan',
        'jabatan' => 'Kasir',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.karyawan.index', ['search' => 'Budi']));
    $response->assertSee('Budi Santoso');
    $response->assertDontSee('Siti Aminah');
});

test('admin can filter karyawan by jabatan', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create([
        'nama' => 'Budi Santoso',
        'email' => 'budi@kote.com',
        'role' => 'karyawan',
        'jabatan' => 'Barista',
    ]);
    User::factory()->create([
        'nama' => 'Siti Aminah',
        'email' => 'siti@kote.com',
        'role' => 'karyawan',
        'jabatan' => 'Kasir',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.karyawan.index', ['jabatan' => 'Kasir']));
    $response->assertSee('Siti Aminah');
    $response->assertDontSee('Budi Santoso');
});

test('admin can view create karyawan page', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('admin.karyawan.create'));

    $response->assertStatus(200);
    $response->assertSee('Tambah Karyawan Baru');
});

test('admin can store a new karyawan with photo', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role' => 'admin']);
    $file = UploadedFile::fake()->create('profile.jpg', 100, 'image/jpeg');

    $response = $this->actingAs($admin)->post(route('admin.karyawan.store'), [
        'nama' => 'Rina Kusuma',
        'email' => 'rina.k@kote.com',
        'password' => 'password123',
        'no_hp' => '0821-9988-7766',
        'jabatan' => 'Barista',
        'jenis_kelamin' => 'P',
        'alamat' => 'Bogor Timur',
        'foto' => $file,
    ]);

    $response->assertRedirect(route('admin.karyawan.index'));
    $this->assertDatabaseHas('users', [
        'nama' => 'Rina Kusuma',
        'email' => 'rina.k@kote.com',
        'no_hp' => '0821-9988-7766',
        'jabatan' => 'Barista',
        'jenis_kelamin' => 'P',
        'role' => 'karyawan',
        'alamat' => 'Bogor Timur',
    ]);

    $user = User::where('email', 'rina.k@kote.com')->first();
    $fotoPath = str_replace('/storage/', '', $user->foto_identitas);
    Storage::disk('public')->assertExists($fotoPath);
});

test('admin can edit an existing karyawan', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $karyawan = User::factory()->create([
        'nama' => 'Budi Santoso',
        'role' => 'karyawan',
        'jabatan' => 'Barista',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.karyawan.edit', $karyawan));

    $response->assertStatus(200);
    $response->assertSee('Edit Karyawan');
    $response->assertSee('Budi Santoso');
});

test('admin can update a karyawan and change photo', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role' => 'admin']);
    $karyawan = User::factory()->create([
        'nama' => 'Budi Santoso',
        'email' => 'budi.s@kote.com',
        'role' => 'karyawan',
        'jabatan' => 'Barista',
        'foto_identitas' => '/storage/karyawan/old.jpg',
    ]);

    $file = UploadedFile::fake()->create('profile_new.jpg', 100, 'image/jpeg');

    $response = $this->actingAs($admin)->put(route('admin.karyawan.update', $karyawan), [
        'nama' => 'Budi Santoso Wibowo',
        'email' => 'budi.s@kote.com',
        'no_hp' => '0812-3456-7890',
        'jabatan' => 'Kasir',
        'jenis_kelamin' => 'L',
        'alamat' => 'Bogor Barat',
        'foto' => $file,
    ]);

    $response->assertRedirect(route('admin.karyawan.index'));
    $this->assertDatabaseHas('users', [
        'id_user' => $karyawan->id_user,
        'nama' => 'Budi Santoso Wibowo',
        'email' => 'budi.s@kote.com',
        'jabatan' => 'Kasir',
        'alamat' => 'Bogor Barat',
    ]);

    $karyawan->refresh();
    $fotoPath = str_replace('/storage/', '', $karyawan->foto_identitas);
    Storage::disk('public')->assertExists($fotoPath);
});

test('admin can delete a karyawan', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role' => 'admin']);
    $karyawan = User::factory()->create([
        'nama' => 'Budi Santoso',
        'role' => 'karyawan',
        'jabatan' => 'Barista',
        'foto_identitas' => '/storage/karyawan/'.rand().'/foto.jpg',
    ]);

    $response = $this->actingAs($admin)->delete(route('admin.karyawan.destroy', $karyawan));

    $response->assertRedirect(route('admin.karyawan.index'));
    $this->assertDatabaseMissing('users', [
        'id_user' => $karyawan->id_user,
    ]);
});
