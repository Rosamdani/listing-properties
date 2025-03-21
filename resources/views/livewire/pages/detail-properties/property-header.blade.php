<?php

use Livewire\Volt\Component;
use App\Models\Properties;

new class extends Component {
    public Properties $property;
}; ?>

<div>
    <ul class="property-detail_meta d-flex align-items-center flex-wrap">
        @if ($property->featured)
            <i>Featured</i>
        @endif
        <li><span class="icon fa-regular fa-calendar fa-fw"></span>{{ $property->available_from->format('d M, Y') }}</li>
        <li><span class="icon fa-solid fa-building fa-fw"></span>{{ $property->type->name }}</li>
        <li><span class="icon fa-solid fa-tag fa-fw"></span>{{ $property->status->label() }}</li>
    </ul>
    <h3 class="property-detail_heading">{{ $property->name }}</h3>
    <div class="property-detail_location"><i class="flaticon-maps-and-flags"></i>{{ $property->location }}</div>
    <div class="property-detail_price mt-2 mb-3">
        <span class="text-primary font-weight-bold h4">${{ number_format($property->price) }}</span>
    </div>
</div>
