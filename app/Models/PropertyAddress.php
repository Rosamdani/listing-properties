<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyAddress extends Model
{
    use HasFactory;

    protected $table = 'property_addresses';

    protected $fillable = [
        'property_id',
        'street_address',
        'unit_number',
        'city',
        'district',
        'province',
        'postal_code',
        'country',
        'latitude',
        'longitude',
        'display_address',
    ];

    public function property()
    {
        return $this->belongsTo(Properties::class);
    }
}
