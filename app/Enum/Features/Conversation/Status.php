<?php

namespace App\Enum\Features\Conversation;

enum Status:string 
{
    case ACTIVE = 'active';
    case ARCHIVED = 'archived';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    public function getLabel(): string
    {
        return trans("features.conversation.status.{$this->value}");
    }
}
