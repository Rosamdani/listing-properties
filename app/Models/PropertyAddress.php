<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'lat',
        'lng',
        'display_address',
    ];

    protected $casts = [
        'location' => 'array',
    ];

    protected $appends = [
        'location',
    ];

    public function location(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => [
                'lat' => (float) ($attributes['lat'] ?? 0),
                'lng' => (float) ($attributes['lng'] ?? 0),
            ],
            set: function ($value) {
                if (is_array($value) && isset($value['lat']) && isset($value['lng'])) {
                    return [
                        'lat' => (float) $value['lat'],
                        'lng' => (float) $value['lng'],
                    ];
                }
                
                return [];
            },
        );
    }


    public function property()
    {
        return $this->belongsTo(Properties::class);
    }
}
