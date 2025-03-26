<?php

namespace App\Enum\Property;

enum TourStatus:string 
{
    case REQUESTED = 'requested';
    case CONFIRMED = 'confirmed';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    public function getLabel(): string
    {
        return trans("property.tour_status.{$this->value}");
    }
}
