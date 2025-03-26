<?php

namespace App\Enum\Neighborhood;

enum EducationType:string 
{
    case NEGERI = 'negeri';
    case SWASTA = 'swasta';
    case INTERNASIONAL = 'internasional';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    public function getLabel(): string
    {
        return trans("neighborhood.education_type.{$this->value}");
    }
}
