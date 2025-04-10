<?php
namespace App\Repositories\Interfaces;

interface FilterRepositoriesInterface
{
    public function getPropertyByLatLong(float $latitude, float $longitude, float $radiusKm = 10);
}