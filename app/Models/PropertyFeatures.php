<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyFeatures extends Model
{
    protected $table = 'property_features';

    protected $fillable = [
        'name',
        'category',
        'icon',
        'is_active',
    ];
}
