<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyPriceHistory extends Model
{
    protected $table = 'property_price_history';

    protected $fillable = [
        'property_id',
        'price',
        'date',
    ];
}
