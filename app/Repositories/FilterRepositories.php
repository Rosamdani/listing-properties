<?php 
namespace App\Repositories;

use App\Models\Properties;
use App\Repositories\Interfaces\FilterRepositoriesInterface;

class FilterRepositories implements FilterRepositoriesInterface
{
    public function getPropertyByLatLong(float $latitude, float $longitude, float $radiusKm = 10) {
        return Properties::select('*')
            ->selectRaw(
                "(6371 * acos(cos(radians(?)) * cos(radians(latitude)) 
                * cos(radians(longitude) - radians(?)) + sin(radians(?)) 
                * sin(radians(latitude)))) AS distance",
                [$latitude, $longitude]
            )->having("distance", "<=", $radiusKm)
            ->orderBy("distance")
            ->get();
    }
}