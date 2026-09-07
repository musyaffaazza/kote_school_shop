<?php

test('halaman tentang kami dapat diakses dan menampilkan konten yang sesuai', function () {
    $response = $this->get(route('about'));

    $response->assertStatus(200);
    $response->assertSee('Dedikasi dalam Setiap Tetes Kopi');
    $response->assertSee('Cerita Kami');
    $response->assertSee('Visi Kami');
    $response->assertSee('Misi Kami');
    $response->assertSee('Kenapa Memilih Kami?');
});
