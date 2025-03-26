<?php

namespace App\Enum;

enum PropertyStatus: string
{
    case FOR_SALE = 'for_sale';
    case FOR_RENT = 'for_rent';
    case SOLD = 'sold';
    case RENTED = 'rented';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    public static function getValues(): array
    {
        return array_map(fn (PropertyStatus $status) => $status->value, self::cases());
    }

    public function label(): string
    {
        return match ($this) {
            self::FOR_SALE => 'For Sale',
            self::FOR_RENT => 'For Rent',
            self::SOLD => 'Sold',
            self::RENTED => 'Rented',
        };
    }
}