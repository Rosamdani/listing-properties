<?php

namespace App\Enum\User;

enum DistanceUnit:string
{
    case KM = 'km';
    case MI = 'mi';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    public function getLabel(): string
    {
        return match($this) 
        {
            self::KM => 'KM',
            self::MI => 'MI',
        };
    }

    public function getUnit(): string
    {
        return match($this) 
        {
            self::KM => 'km',
            self::MI => 'mi',
        };
    }

    public function getIcon(): string
    {
        return match($this) 
        {
            self::KM => 'feather:map-pin',
            self::MI => 'feather:map-pin',
        };
    }
}
