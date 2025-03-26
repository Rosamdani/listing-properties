<?php

namespace App\Enum\Property;

enum EventPrice:string
{
    case LISTED = 'listed';
    case SOLD = 'sold';
    case PRICE_CHANGE = 'price_change';
    case RENTED = 'rented';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    public function getLabel(): string
    {
        return trans("property.event_price.{$this->value}");
    }
}
