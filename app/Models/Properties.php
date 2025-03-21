<?php

namespace App\Models;

use App\Enum\PropertyStatus;
use App\Enum\PropertyType;
use Illuminate\Database\Eloquent\Concerns\HasVersion4Uuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Properties extends Model implements HasMedia
{
    use HasVersion4Uuids, HasSEO, InteractsWithMedia;

    protected $table = 'properties';

    protected $fillable = [
        'name',
        'type',
        'price',
        'location',
        'latitude',
        'longitude',
        'bedrooms',
        'bathrooms',
        'garages',
        'area',
        'furnished',
        'available_from',
        'description',
        'status',
        'featured',
        'amenities',
        'contact_name',
        'contact_email',
        'contact_phone',
        'slug',
    ];

    protected $casts = [
        'type' => PropertyType::class,
        'status' => PropertyStatus::class,
        'furnished' => 'boolean',
        'featured' => 'boolean',
        'amenities' => 'array',
        'available_from' => 'date',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->useFallbackUrl(asset('assets/images/resource/property-1.jpg'));
            
        $this->addMediaCollection('thumbnail')
            ->singleFile()
            ->useFallbackUrl(asset('assets/images/resource/property-1.jpg'));
    }

    public function getThumbnailAttribute()
    {
        return $this->getFirstMediaUrl('thumbnail') ?: $this->getFirstMediaUrl('images') ?: asset('assets/images/resource/property-1.jpg');
    }

    public function getImagesAttribute()
    {
        return $this->getMedia('images');
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp' . number_format($this->price, 2);
    }

    public function getFormattedAreaAttribute()
    {
        return $this->area . ' m²';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($property) {
            if (empty($property->slug)) {
                $property->slug = Str::slug($property->name);
            }
        });
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class, 'property_id');
    }

    public function primaryImage()
    {
        return $this->hasOne(PropertyImage::class, 'property_id')->where('is_primary', true);
    }
}
