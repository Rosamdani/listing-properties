<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'license_number',
        'agency_name',
        'experience_years',
        'specializations',
        'service_areas',
        'average_rating',
        'total_sales',
        'total_sales_volume',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function properties()
    {
        return $this->hasMany(Properties::class);
    }

}
