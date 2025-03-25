<?php

namespace App\Enum\Property;

enum ListingType: string
{
    case SALE = 'sale';
    case RENT = 'rent';

    public function getLabel():string
    {
        return trans("property.listing_type.{$this->value}");
    }
}
