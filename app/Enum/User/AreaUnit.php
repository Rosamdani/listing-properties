<?php

namespace App\Enum\User;

enum AreaUnit:string
{
    case M = 'm';
    case FT = 'ft';

    public function getLabel(): string
    {
        return match($this) 
        {
            self::M => 'M',
            self::FT => 'FT',
        };
    }

    public function getUnit(): string
    {
        return match($this) 
        {
            self::M => 'm',
            self::FT => 'ft',
        };
    }
}
