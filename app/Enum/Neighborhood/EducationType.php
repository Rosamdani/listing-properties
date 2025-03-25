<?php

namespace App\Enum\Neighborhood;

enum EducationType:string 
{
    case NEGERI = 'negeri';
    case SWASTA = 'swasta';
    case INTERNASIONAL = 'internasional';

    public function getLabel(): string
    {
        return trans("neighborhood.education_type.{$this->value}");
    }
}
