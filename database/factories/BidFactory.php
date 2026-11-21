<?php

namespace Database\Factories;

use App\Models\Bid;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bid>
 */
class BidFactory extends Factory
{
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'developer_id' => User::factory()->developer(),
            'harga' => fake()->numberBetween(1, 20) * 500_000,
            'durasi' => fake()->numberBetween(7, 60),
            'proposal' => fake()->sentence(12),
            'is_winner' => false,
        ];
    }
}
