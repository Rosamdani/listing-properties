<?php

namespace App\Enum\User;

enum AreaUnit:string
{
    case M = 'm';
    case FT = 'ft';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    public function getLabel(): string
    {
        return match($this) 
        {
            self::M => 'M',
            self::FT => 'FT',
        };
    }

    public function getUnit(): string
    {
        return match($this) 
        {
            self::M => 'm',
            self::FT => 'ft',
        };
    }
}
