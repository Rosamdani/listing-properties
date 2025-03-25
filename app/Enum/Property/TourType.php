<?php

namespace App\Enum\Property;

enum TourType:string 
{
    case IN_PERSON = 'in-person';
    case VIRTUAL = 'virtual';

    public function getLabel(): string
    {
        return trans("property.tour_type.{$this->value}");
    }
}
