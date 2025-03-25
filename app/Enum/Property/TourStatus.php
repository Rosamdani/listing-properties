<?php

namespace App\Enum\Property;

enum TourStatus:string 
{
    case REQUESTED = 'requested';
    case CONFIRMED = 'confirmed';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function getLabel(): string
    {
        return trans("property.tour_status.{$this->value}");
    }
}
