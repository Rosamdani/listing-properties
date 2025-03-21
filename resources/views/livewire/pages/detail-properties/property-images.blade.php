<?php

use Livewire\Volt\Component;
use App\Models\Properties;

new class extends Component {
    public Properties $property;

    public function placeholder()
    {
        return <<<'HTML'
        <div class="property-detail_image skeleton-loader">
            <div style="height: 400px; background: #eee; animation: pulse 1.5s infinite;"></div>
        </div>
        HTML;
    }
}; ?>

<div>
    <div class="property-detail_image">
        @if ($property['thumbnail'])
            <img src="{{ $property['thumbnail'] }}" alt="{{ $property->name }}" />
        @else
            <img src="{{ asset('assets/images/placeholder.jpg') }}" alt="{{ $property->name }}" />
        @endif
    </div>
    @if ($property->getMedia('property_images')->count() > 1)
        <div class="carousel-box mt-4">
            <div class="single-item_slider swiper-container">
                <div class="swiper-wrapper">
                    @foreach ($property->getMedia('property_images')->skip(1) as $image)
                        <div class="swiper-slide">
                            <div class="image">
                                <img src="{{ $image->getUrl() }}"
                                    alt="{{ $property->name }} - Image {{ $loop->iteration }}" />
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="single-item_slider-prev fas fa-angle-left fa-fw"></div>
                <div class="single-item_slider-next fas fa-angle-right fa-fw"></div>
            </div>
        </div>
    @endif
</div>
