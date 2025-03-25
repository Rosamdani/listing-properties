<?php

namespace App\Enum\Features\SavedSearch;

enum NotifyFrequency:string 
{
    case DAILY = 'daily';
    case WEEKLY = 'weekly';
    case NONE = 'none';

    public function getLabel(): string
    {
        return trans("features.saved_search.notify_frequency.{$this->value}");
    }
}
