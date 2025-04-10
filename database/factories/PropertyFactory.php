<?php

namespace Database\Factories;

use App\Enum\Property\Furnished;
use App\Enum\Property\ListingType;
use App\Enum\Property\Status;
use App\Enum\PropertyType;
use App\Models\Agent;
use App\Models\Properties;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PropertyFactory extends Factory
{
    protected $model = Properties::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id' => User::inRandomOrder()->first()->id,
            'property_type' => $this->faker->randomElement(PropertyType::values()),
            'listing_type' => $this->faker->randomElement(ListingType::values()),
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 100000, 10000000),
            'currency' => 'IDR',
            'bedrooms' => $this->faker->numberBetween(1, 10),
            'bathrooms' => $this->faker->randomFloat(1, 1, 5),
            'building_size' => $this->faker->randomFloat(2, 50, 500),
            'land_size' => $this->faker->randomFloat(2, 100, 1000),
            'year_built' => $this->faker->year,
            'floors' => $this->faker->numberBetween(1, 3),
            'parking_spots' => $this->faker->numberBetween(0, 5),
            'furnished' => $this->faker->randomElement(Furnished::values()),
            'status' => $this->faker->randomElement(Status::values()),
            'published_at' => $this->faker->dateTime,
            'expires_at' => $this->faker->dateTime,
            'is_featured' => $this->faker->boolean,
            'is_verified' => $this->faker->boolean,
            'view_count' => $this->faker->numberBetween(0, 1000),
            'slug' => $this->faker->slug,
            'virtual_tour_url' => $this->faker->url,
        ];
    }
}
