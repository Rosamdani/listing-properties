<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyDocuments extends Model
{
    protected $table = 'property_documents';

    protected $fillable = [
        'property_id',
        'title',
        'description',
        'is_public',
    ];
}
