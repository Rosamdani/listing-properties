<?php

namespace App\Enum\Neighborhood;

enum AmenitiesType:string 
{
    case MALL = 'mall';
    case HOSPITAL = 'hospital';
    case MARKET = 'market';
    case PARK = 'park';
    case RESTAURANT = 'restaurant';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    public function getLabel(): string
    {
        return trans("neighborhood.amenities_type.{$this->value}");
    }
}
