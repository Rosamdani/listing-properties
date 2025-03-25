<?php

namespace App\Enum\Property;

enum EventPrice:string
{
    case LISTED = 'listed';
    case SOLD = 'sold';
    case PRICE_CHANGE = 'price_change';
    case RENTED = 'rented';

    public function getLabel(): string
    {
        return trans("property.event_price.{$this->value}");
    }
}
