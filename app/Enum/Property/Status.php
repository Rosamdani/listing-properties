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

    public function getColor(): string
    {
        return match ($this) {
            self::DRAFT => 'primary',
            self::ACTIVE => 'success',
            self::PENDING => 'warning',
            self::SOLD => 'danger',
            self::RENTED => 'info',
        };
    }
}
