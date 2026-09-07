<?php

use App\Models\Promo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('guests are redirected to login when trying to access admin promo pages', function () {
    $promo = Promo::create([
        'nama_promo' => 'Diskon Awal Tahun',
        'jenis_promo' => 'diskon',
        'nilai_promo' => 5000,
        'satuan_nilai' => 'rupiah',
    ]);

    $this->get(route('admin.promo.index'))->assertRedirect(route('login'));
    $this->get(route('admin.promo.create'))->assertRedirect(route('login'));
    $this->get(route('admin.promo.edit', $promo))->assertRedirect(route('login'));
});

test('pelanggan cannot access admin promo pages', function () {
    $user = User::factory()->create(['role' => 'pelanggan']);
    $promo = Promo::create([
        'nama_promo' => 'Diskon Awal Tahun',
        'jenis_promo' => 'diskon',
        'nilai_promo' => 5000,
        'satuan_nilai' => 'rupiah',
    ]);

    $this->actingAs($user)->get(route('admin.promo.index'))->assertStatus(403);
    $this->actingAs($user)->get(route('admin.promo.create'))->assertStatus(403);
    $this->actingAs($user)->get(route('admin.promo.edit', $promo))->assertStatus(403);
});

test('admin can view promo list in admin dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $promo = Promo::create([
        'nama_promo' => 'Promo Spesial Kote',
        'deskripsi' => 'Diskon mantap',
        'jenis_promo' => 'diskon',
        'nilai_promo' => 3000,
        'satuan_nilai' => 'rupiah',
        'kode_voucher' => 'KOTE3K',
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.promo.index'));

    $response->assertStatus(200);
    $response->assertSee('Daftar Promo');
    $response->assertSee('Promo Spesial Kote');
    $response->assertSee('KOTE3K');
});

test('admin can create promo and it appears on user promo page', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role' => 'admin']);
    $image = UploadedFile::fake()->create('banner.jpg', 100, 'image/jpeg');

    $response = $this->actingAs($admin)->post(route('admin.promo.store'), [
        'nama_promo' => 'Promo Lebaran Hemat',
        'deskripsi' => 'Diskon meriah menyambut hari raya',
        'jenis_promo' => 'diskon',
        'nilai_promo' => 5000,
        'satuan_nilai' => 'rupiah',
        'kode_voucher' => 'LEBARAN5K',
        'periode_mulai' => now()->toDateString(),
        'periode_selesai' => now()->addMonth()->toDateString(),
        'gambar' => $image,
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('admin.promo.index'));
    $this->assertDatabaseHas('promos', [
        'nama_promo' => 'Promo Lebaran Hemat',
        'kode_voucher' => 'LEBARAN5K',
        'is_active' => true,
    ]);

    // Check user promo page
    $userResponse = $this->get(route('promo'));
    $userResponse->assertStatus(200);
    $userResponse->assertSee('Promo Lebaran Hemat');
    $userResponse->assertSee('LEBARAN5K');
    $userResponse->assertSee('Diskon meriah menyambut hari raya');
});

test('admin can toggle promo status and user promo page reflects it', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $promo = Promo::create([
        'nama_promo' => 'Promo Kilat 24 Jam',
        'deskripsi' => 'Promo kilat',
        'jenis_promo' => 'diskon',
        'nilai_promo' => 4000,
        'satuan_nilai' => 'rupiah',
        'is_active' => true,
    ]);

    // Verify it is visible to user
    $this->get(route('promo'))->assertSee('Promo Kilat 24 Jam');

    // Admin deactivates promo
    $response = $this->actingAs($admin)->post(route('admin.promo.toggle', $promo));
    $response->assertRedirect();
    $promo->refresh();
    expect($promo->is_active)->toBeFalse();

    // Verify it is no longer visible to user
    $this->get(route('promo'))->assertDontSee('Promo Kilat 24 Jam');
});

test('admin can update promo and user promo page displays updated data', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $promo = Promo::create([
        'nama_promo' => 'Promo Lama',
        'deskripsi' => 'Deskripsi lama',
        'jenis_promo' => 'diskon',
        'nilai_promo' => 1000,
        'satuan_nilai' => 'rupiah',
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)->put(route('admin.promo.update', $promo), [
        'nama_promo' => 'Promo Baru Keren',
        'deskripsi' => 'Deskripsi baru yang lebih mantap',
        'jenis_promo' => 'voucher',
        'nilai_promo' => 20,
        'satuan_nilai' => 'persen',
        'kode_voucher' => 'KEREN20',
        'is_active' => '1',
    ]);

    $response->assertRedirect(route('admin.promo.index'));

    $userResponse = $this->get(route('promo'));
    $userResponse->assertSee('Promo Baru Keren');
    $userResponse->assertSee('Deskripsi baru yang lebih mantap');
    $userResponse->assertSee('KEREN20');
    $userResponse->assertDontSee('Promo Lama');
});

test('admin can delete promo and it is removed from user promo page', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $promo = Promo::create([
        'nama_promo' => 'Promo Akan Dihapus',
        'deskripsi' => 'Deskripsi promo',
        'jenis_promo' => 'diskon',
        'nilai_promo' => 2000,
        'satuan_nilai' => 'rupiah',
        'is_active' => true,
    ]);

    $this->get(route('promo'))->assertSee('Promo Akan Dihapus');

    $response = $this->actingAs($admin)->delete(route('admin.promo.destroy', $promo));
    $response->assertRedirect(route('admin.promo.index'));
    $this->assertDatabaseMissing('promos', ['id' => $promo->id]);

    $this->get(route('promo'))->assertDontSee('Promo Akan Dihapus');
});
