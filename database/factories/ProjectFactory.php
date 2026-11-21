<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $min = fake()->numberBetween(1, 10) * 1_000_000;

        return [
            'client_id' => User::factory()->klien(),
            'judul' => fake()->sentence(4),
            'deskripsi' => fake()->paragraph(),
            'kategori' => fake()->randomElement(['Web', 'Mobile', 'Desain', 'Data', 'AI']),
            'budget_min' => $min,
            'budget_max' => $min * 2,
            'batas_lelang' => now()->addMonth()->toDateString(),
            'status' => Project::STATUS_OPEN,
        ];
    }
}
