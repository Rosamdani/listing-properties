<?php

namespace App\Enum\Features\Conversation;

enum Status:string 
{
    case ACTIVE = 'active';
    case ARCHIVED = 'archived';

    public function getLabel(): string
    {
        return trans("features.conversation.status.{$this->value}");
    }
}
