<?php

namespace App\Models;

use App\Enum\Property\Furnished;
use App\Enum\Property\ListingType;
use App\Enum\Property\Status;
use App\Enum\PropertyType;
use Database\Factories\PropertyFactory;
use Illuminate\Database\Eloquent\Concerns\HasVersion4Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Properties extends Model implements HasMedia
{
    use HasSEO, InteractsWithMedia, HasFactory;

    protected $table = 'properties';

    protected $fillable = [
        'owner_id',
        'agent_id',
        'property_type',
        'listing_type',
        'title',
        'description',
        'price',
        'currency',
        'bedrooms',
        'bathrooms',
        'building_size',
        'land_size',
        'year_built',
        'floors',
        'parking_spots',
        'furnished',
        'status',
        'published_at',
        'expires_at',
        'is_featured',
        'is_verified',
        'view_count',
        'slug',
        'virtual_tour_url',
    ];

    protected $casts = [
        'property_type' => PropertyType::class,
        'listing_type' => ListingType::class,
        'price' => 'decimal:2',
        'bathrooms' => 'decimal:1',
        'building_size' => 'decimal:2',
        'land_size' => 'decimal:2',
        'furnished' => Furnished::class,
        'status' => Status::class,
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'view_count' => 'integer',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return PropertyFactory::new();
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('properties')
            ->useFallbackUrl(asset('assets/images/resource/property-1.jpg'));
            
        $this->addMediaCollection('propertu_thumbnail')
            ->singleFile()
            ->useFallbackUrl(asset('assets/images/resource/property-1.jpg'));
    }

    public function getThumbnailAttribute()
    {
        return $this->getFirstMediaUrl('thumbnail') ?: $this->getFirstMediaUrl('properties') ?: asset('assets/images/resource/property-1.jpg');
    }

    public function getImagesAttribute()
    {
        return $this->getMedia('images');
    }

    public function getFormattedPriceAttribute()
    {
        return $this->currency . ' ' . number_format($this->price, 2);
    }

    public function getFormattedAreaAttribute()
    {
        return $this->building_size . ' m²';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($property) {
            if (empty($property->slug)) {
                $property->slug = Str::slug($property->title);
            }
        });
    }

    public function propertyAddress()
    {
        return $this->hasOne(PropertyAddress::class, 'property_id', 'id');
    }

    public function features()
    {
        return $this->belongsToMany(PropertyFeatures::class, 'property_feature_mappings', 'property_id', 'feature_id');
    }

    public function priceHistory()
    {
        return $this->hasMany(PropertyPriceHistory::class, 'property_id', 'id');
    }

    public function valueEstimates()
    {
        return $this->hasMany(PropertyValueEstimate::class, 'property_id', 'id');
    }

    public function documents()
    {
        return $this->hasMany(PropertyDocuments::class, 'property_id', 'id');
    }
}
