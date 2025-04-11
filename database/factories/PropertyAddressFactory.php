<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyAddress>
 */
class PropertyAddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'street_address' => $this->faker->streetAddress(),
            'unit_number' => $this->faker->secondaryAddress(),
            'city' => $this->faker->city(),
            'district' => $this->faker->citySuffix(),
            'province' => $this->faker->state(),
            'postal_code' => $this->faker->postcode(),
            'country' => 'Indonesia',
            'lat' => $this->faker->latitude(),
            'lng' => $this->faker->longitude(),
            'display_address' => true,
        ];
    }
}
