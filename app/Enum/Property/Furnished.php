<?php

namespace App\Enum\Property;

enum Furnished:string
{
    case UNFURNISHED = 'unfurnished';
    case SEMI = 'semi';
    case FULLY = 'fully';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
    
    public function getLabel(): string
    {
        return trans("property.furnished.{$this->value}");
    }
}
