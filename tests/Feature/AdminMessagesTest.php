<?php

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guests are redirected to login when trying to access admin messages', function () {
    $response = $this->get(route('admin.messages.index'));

    $response->assertRedirect(route('login'));
});

test('pelanggan cannot access admin messages', function () {
    $user = User::factory()->create(['role' => 'pelanggan']);

    $response = $this->actingAs($user)->get(route('admin.messages.index'));

    $response->assertStatus(403);
});

test('karyawan cannot access admin messages', function () {
    $user = User::factory()->create(['role' => 'karyawan']);

    $response = $this->actingAs($user)->get(route('admin.messages.index'));

    $response->assertStatus(403);
});

test('admin can access admin messages successfully and view list', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $message = ContactMessage::create([
        'nama' => 'Andi Pratama',
        'email' => 'andi@email.com',
        'subjek' => 'Saran / Masukan',
        'pesan' => 'Ini adalah saran dari Andi.',
        'is_read' => false,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.messages.index'));

    $response->assertStatus(200);
    $response->assertSee('Pesan Masuk');
    $response->assertSee('Kelola pesan');
    $response->assertSee('Andi Pratama');
    $response->assertSee('Saran & Masukan');
});

test('admin can filter messages by subject', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    ContactMessage::create([
        'nama' => 'Andi Pratama',
        'email' => 'andi@email.com',
        'subjek' => 'Saran / Masukan',
        'pesan' => 'Ini adalah saran.',
    ]);

    ContactMessage::create([
        'nama' => 'Citra Lestari',
        'email' => 'citra@email.com',
        'subjek' => 'Kerja Sama',
        'pesan' => 'Ini adalah kerjasama.',
    ]);

    // Filter to 'Kerja Sama'
    $response = $this->actingAs($admin)->get(route('admin.messages.index', ['filter' => 'Kerja Sama']));

    $response->assertStatus(200);
    $response->assertSee('Citra Lestari');
    $response->assertDontSee('Andi Pratama');
});

test('admin can search messages', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    ContactMessage::create([
        'nama' => 'Andi Pratama',
        'email' => 'andi@email.com',
        'subjek' => 'Saran / Masukan',
        'pesan' => 'Kopi susu gula aren.',
    ]);

    ContactMessage::create([
        'nama' => 'Citra Lestari',
        'email' => 'citra@email.com',
        'subjek' => 'Kerja Sama',
        'pesan' => 'Biji kopi lokal.',
    ]);

    // Search for 'gula aren'
    $response = $this->actingAs($admin)->get(route('admin.messages.index', ['search' => 'gula aren']));

    $response->assertStatus(200);
    $response->assertSee('Andi Pratama');
    $response->assertDontSee('Citra Lestari');
});

test('admin can delete a message', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $message = ContactMessage::create([
        'nama' => 'Andi Pratama',
        'email' => 'andi@email.com',
        'subjek' => 'Saran / Masukan',
        'pesan' => 'Pesan uji coba hapus.',
    ]);

    $response = $this->actingAs($admin)->delete(route('admin.messages.destroy', $message));

    $response->assertRedirect(route('admin.messages.index'));
    $this->assertDatabaseMissing('contact_messages', ['id' => $message->id]);
});
