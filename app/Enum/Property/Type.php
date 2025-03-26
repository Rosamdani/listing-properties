<?php

namespace App\Enum\Property;

enum Type:string
{
    case APARTMENT = 'apartment';
    case HOUSE = 'house';
    case LAND = 'land';
    case COMMERCIAL = 'commercial';
    case VILLA = 'villa';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    public function getLabel(): string
    {
        return trans("property.type.{$this->value}");
    }
}
