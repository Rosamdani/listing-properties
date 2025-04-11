<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Properties;
use App\Models\PropertyAddress;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'email' => 'admin@gmail.com',
            'first_name' => 'Admin', 
            'last_name' => 'Admin', 
            'phone' => '1234567890',
            'password' => Hash::make('password'),
        ]);
        User::factory(30)->create()->each(function ($user) {
            $agent = Agent::factory()
                ->for($user)
                ->has(Properties::factory()->count(5)->has(PropertyAddress::factory(), 'propertyAddress'), 'properties')
                ->create();
        });
    }
}
