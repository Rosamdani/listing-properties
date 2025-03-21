<?php

use Livewire\Volt\Component;
use App\Models\Properties;

new class extends Component {
    public Properties $property;
}; ?>

<div>
    <h4 class="property-detail_subheading mt-4">Properties Amenities</h4>
    <p>The property comes with these amenities to enhance your comfort and convenience.</p>

    <div class="property-detail_checks">
        <div class="row clearfix">
            @php
                $chunkedAmenities = collect($property->amenities)->chunk(ceil(count($property->amenities) / 3));
            @endphp

            @foreach ($chunkedAmenities as $amenityChunk)
                <!-- Column -->
                <div class="column col-lg-4 col-md-4 col-sm-6">
                    <ul class="property-detail_checklist">
                        @foreach ($amenityChunk as $amenity)
                            <li><i class="flaticon-checked"></i>{{ $amenity }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</div>
