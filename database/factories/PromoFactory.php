<?php

namespace Database\Factories;

use App\Models\Promo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Promo>
 */
class PromoFactory extends Factory
{
    protected $model = Promo::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_promo' => fake()->words(3, true),
            'deskripsi' => fake()->sentence(),
            'jenis_promo' => fake()->randomElement(['diskon', 'paket', 'voucher']),
            'nilai_promo' => fake()->numberBetween(1000, 5000),
            'satuan_nilai' => 'rupiah',
            'kode_voucher' => strtoupper(fake()->unique()->bothify('????####')),
            'periode_mulai' => now(),
            'periode_selesai' => now()->addMonth(),
            'is_active' => true,
        ];
    }

    /**
     * Promo yang tidak aktif.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Promo dengan tipe persentase.
     */
    public function persen(): static
    {
        return $this->state(fn (array $attributes) => [
            'nilai_promo' => fake()->numberBetween(5, 50),
            'satuan_nilai' => 'persen',
        ]);
    }

    /**
     * Promo permanent (tanpa batas waktu).
     */
    public function permanent(): static
    {
        return $this->state(fn (array $attributes) => [
            'periode_mulai' => null,
            'periode_selesai' => null,
        ]);
    }
}
