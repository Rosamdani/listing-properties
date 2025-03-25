<?php

namespace App\Enum\Property;

enum Furnished:string
{
    case UNFURNISHED = 'unfurnished';
    case SEMI = 'semi';
    case FULLY = 'fully';

    public function getLabel(): string
    {
        return trans("property.furnished.{$this->value}");
    }
}
