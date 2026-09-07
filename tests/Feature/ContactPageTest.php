<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('halaman kontak dapat diakses dan menampilkan konten yang sesuai', function () {
    $response = $this->get(route('contact'));

    $response->assertStatus(200);
    $response->assertSee('Hubungi Kami');
    $response->assertSee('Informasi Kontak');
    $response->assertSee('Temukan Kami');
    $response->assertSee('Kirim Pesan');
});

test('formulir kontak dapat dikirim dengan data valid', function () {
    $response = $this->post(route('contact.submit'), [
        'nama' => 'Test User',
        'email' => 'test@example.com',
        'subjek' => 'Saran / Masukan',
        'pesan' => 'Ini adalah pesan pengujian.',
    ]);

    $response->assertRedirect(route('contact'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('contact_messages', [
        'nama' => 'Test User',
        'email' => 'test@example.com',
        'subjek' => 'Saran / Masukan',
        'pesan' => 'Ini adalah pesan pengujian.',
    ]);
});

test('formulir kontak gagal tanpa data yang diperlukan', function () {
    $response = $this->post(route('contact.submit'), []);

    $response->assertSessionHasErrors(['nama', 'email', 'subjek', 'pesan']);
});
