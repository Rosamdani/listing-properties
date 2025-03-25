<?php

namespace App\Enum\Property;

enum Status:string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case PENDING = 'pending';
    case SOLD = 'sold';
    case RENTED = 'rented';

    public function getLabel(): string
    {
        return trans("property.status.{$this->value}");
    }
}
