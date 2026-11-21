<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'klien',
            'remember_token' => Str::random(10),
        ];
    }

    public function klien(): static
    {
        return $this->state(['role' => 'klien']);
    }

    public function developer(): static
    {
        return $this->state(['role' => 'developer']);
    }

    public function admin(): static
    {
        return $this->state(['role' => 'admin']);
    }
}
