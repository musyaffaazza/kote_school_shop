<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'no_hp' => '08'.fake()->numerify('##########'),
            'nis' => fake()->unique()->numerify('##########'),
            'foto_identitas' => null,
            'kelas' => 'XI RPL 1',
            'jenis_kelamin' => 'L',
            'role' => 'pelanggan',
            'alamat' => fake()->address(),
        ];
    }
}
