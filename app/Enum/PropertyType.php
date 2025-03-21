<?php

namespace App\Enum;

enum PropertyType: string
{
    case HOUSE = 'house';
    case APARTMENT = 'apartment';
    case COMMERCIAL = 'commercial';
    case LAND = 'land';

    public static function getValues(): array
    {
        return array_map(fn (PropertyType $type) => $type->value, self::cases());
    }

    public function label(): string
    {
        return match ($this) {
            self::HOUSE => 'House',
            self::APARTMENT => 'Apartment',
            self::COMMERCIAL => 'Commercial',
            self::LAND => 'Land',
        };
    }
}
