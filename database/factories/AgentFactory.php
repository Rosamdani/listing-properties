<?php

namespace Database\Factories;

use App\Models\Agent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Agent>
 */
class AgentFactory extends Factory
{
    protected $model = Agent::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'license_number' => $this->faker->optional()->regexify('[A-Z0-9]{10}'),
            'agency_name' => $this->faker->optional()->company(),
            'experience_years' => $this->faker->optional()->numberBetween(0, 40),
            'specializations' => json_encode($this->faker->optional()->randomElements(['Residential', 'Commercial', 'Industrial'], $count = rand(1, 3))),
            'service_areas' => json_encode($this->faker->optional()->randomElements(['New York', 'Los Angeles', 'Chicago'], $count = rand(1, 3))),
            'average_rating' => $this->faker->randomFloat(2, 0, 5),
            'total_sales' => $this->faker->numberBetween(0, 1000),
            'total_sales_volume' => $this->faker->randomFloat(2, 0, 1000000),
        ];
    }
}
