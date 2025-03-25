<?php

namespace App\Enum\Neighborhood;

enum EducationLevel:string 
{
    case TK = 'TK';
    case SD = 'SD';
    case SMP = 'SMP';
    case SMA = 'SMA';
    case PT = 'PT';

    public function getLabel(): string
    {
        return trans("neighborhood.education_level.{$this->value}");
    }
}
