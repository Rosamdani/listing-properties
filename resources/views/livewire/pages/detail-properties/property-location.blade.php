<?php

use Livewire\Volt\Component;
use App\Models\Properties;

new class extends Component {
    public Properties $property;
}; ?>

<div>
    <h4 class="property-detail_subheading mt-4">Properties Location</h4>
    <div class="map-box">
        <iframe
            src="https://maps.google.com/maps?q={{ $property->latitude }},{{ $property->longitude }}&z=15&output=embed"
            width="100%" height="400" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false"
            tabindex="0">
        </iframe>
    </div>
</div>
