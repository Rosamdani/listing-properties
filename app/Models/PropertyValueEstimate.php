<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyValueEstimate extends Model
{
    protected $table = 'property_value_estimates';

    protected $fillable = [
        'property_id',
        'estimate',
    ];
}
