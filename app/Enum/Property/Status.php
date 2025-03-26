<?php

namespace App\Enum\Property;

enum Status:string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case PENDING = 'pending';
    case SOLD = 'sold';
    case RENTED = 'rented';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }
    
    public function getLabel(): string
    {
        return trans("property.status.{$this->value}");
    }
}
