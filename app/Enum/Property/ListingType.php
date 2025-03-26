<?php

namespace App\Enum\Property;

enum ListingType: string
{
    case SALE = 'sale';
    case RENT = 'rent';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    public function getLabel():string
    {
        return trans("property.listing_type.{$this->value}");
    }
}
