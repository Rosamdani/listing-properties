<?php

namespace App\Enum\Property;

enum Type:string
{
    case APARTMENT = 'apartment';
    case HOUSE = 'house';
    case LAND = 'land';
    case COMMERCIAL = 'commercial';
    case VILLA = 'villa';

    public function getLabel(): string
    {
        return trans("property.type.{$this->value}");
    }
}
