<?php 
namespace App\Services;

use App\Models\Properties;
use App\Models\Property;

class FilterService
{
    public function filterBy($request) {
        $properties = Properties::filter($request)->paginate(12);
        return $properties;
    }
}