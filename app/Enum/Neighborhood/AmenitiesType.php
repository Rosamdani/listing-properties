<?php

namespace App\Enum\Neighborhood;

enum AmenitiesType:string 
{
    case MALL = 'mall';
    case HOSPITAL = 'hospital';
    case MARKET = 'market';
    case PARK = 'park';
    case RESTAURANT = 'restaurant';

    public function getLabel(): string
    {
        return trans("neighborhood.amenities_type.{$this->value}");
    }
}
