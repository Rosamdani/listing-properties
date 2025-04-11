<?php

namespace App\Observers;

use App\Models\Properties;
use Illuminate\Support\Str;

class PropertyObserver
{
    public function created(Properties $property): void
    {
        $this->updateSEO($property);
    }

    /**
     * Handle the Properties "updated" event.
     */
    public function updated(Properties $property): void
    {
        $this->updateSEO($property);
    }

    /**
     * Update or create SEO data for property
     */
    private function updateSEO(Properties $property): void
    {
        $address = $property->propertyAddress;
        $location = $address ? "{$address->city}, {$address->country}" : '';
        
        $title = $property->title;
        if ($property->bedrooms || $property->bathrooms) {
            $bedroomText = $property->bedrooms ? "{$property->bedrooms} BR" : '';
            $bathroomText = $property->bathrooms ? "{$property->bathrooms} Bath" : '';
            $roomInfo = trim("{$bedroomText} {$bathroomText}");
            $title = "{$title} - {$roomInfo}";
        }
        
        if ($location) {
            $title = "{$title} | {$location}";
        }
        
        $description = Str::limit(strip_tags($property->description), 160);
        if (empty($description)) {
            $propertyType = $property->property_type?->value ?? 'property';
            $listingType = $property->listing_type?->value ?? 'for sale';
            $priceInfo = $property->formatted_price ?? '';
            $sizeInfo = $property->building_size ? "with {$property->building_size} m²" : '';
            
            $description = "Discover this {$propertyType} {$listingType} {$priceInfo} {$sizeInfo}";
            if ($location) {
                $description .= " in {$location}";
            }
            $description .= ".";
        }
        
        $image = $property->thumbnail;
        
        $property->seo()->update([
            'title' => $title,
            'description' => $description,
            'image' => $image,
            'author' => 'Property Website',
            'robots' => 'index, follow',
            'canonical_url' => route('properties.show', $property->slug),
        ]);
    }
}
