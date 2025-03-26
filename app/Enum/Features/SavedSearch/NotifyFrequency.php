<?php

namespace App\Enum\Features\SavedSearch;

enum NotifyFrequency:string 
{
    case DAILY = 'daily';
    case WEEKLY = 'weekly';
    case NONE = 'none';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    public function getLabel(): string
    {
        return trans("features.saved_search.notify_frequency.{$this->value}");
    }
}
