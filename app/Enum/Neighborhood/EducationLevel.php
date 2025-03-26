<?php

namespace App\Enum\Neighborhood;

enum EducationLevel:string 
{
    case TK = 'TK';
    case SD = 'SD';
    case SMP = 'SMP';
    case SMA = 'SMA';
    case PT = 'PT';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    public function getLabel(): string
    {
        return trans("neighborhood.education_level.{$this->value}");
    }
}
