<?php

use Livewire\Volt\Component;
use App\Models\Properties;

new class extends Component {
    public Properties $property;
}; ?>

<div>
    <h4 class="property-detail_subheading mt-4">Facts and Features</h4>
    <div class="row clearfix">
        <!-- Property Block Two -->
        <div class="property-block_two col-lg-4 col-md-4 col-sm-6">
            <div class="property-block_two-inner">
                <div class="property-block_two-icon flaticon-double-bed"></div>
                <h6 class="property-block_two-title">Total Bed Count</h6>
                <div class="property-block_two-text">{{ $property->bedrooms }} Beds</div>
            </div>
        </div>

        <!-- Property Block Two -->
        <div class="property-block_two col-lg-4 col-md-4 col-sm-6">
            <div class="property-block_two-inner">
                <div class="property-block_two-icon flaticon-bath-tub"></div>
                <h6 class="property-block_two-title">Bathroom for Use</h6>
                <div class="property-block_two-text">{{ $property->bathrooms }} Bathroom</div>
            </div>
        </div>

        <!-- Property Block Two -->
        <div class="property-block_two col-lg-4 col-md-4 col-sm-6">
            <div class="property-block_two-inner">
                <div class="property-block_two-icon flaticon-scale"></div>
                <h6 class="property-block_two-title">Total Area Size</h6>
                <div class="property-block_two-text">{{ $property->area }} sqft</div>
            </div>
        </div>
    </div>
</div>
