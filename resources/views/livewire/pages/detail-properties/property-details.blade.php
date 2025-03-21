<?php

use Livewire\Volt\Component;
use App\Models\Properties;

new class extends Component {
    public Properties $property;
}; ?>

<div>
    <h4 class="property-detail_subheading mt-4">Properties Details</h4>
    <div class="propert-info">
        <div class="row clearfix">
            <!-- Column -->
            <div class="column col-lg-6 col-md-12 col-sm-12">
                <ul class="propert-info_list">
                    <li>{{ __('Luas Rumah') }}<span>{{ $property->area }} sqft</span></li>
                    <li>{{ __('Jumlah Kamar Tidur') }}<span>{{ $property->bedrooms }}</span></li>
                    <li>{{ __('Jumlah Kamar Mandi') }}<span>{{ $property->bathrooms }}</span></li>
                    <li>{{ __('Tersedia Dari') }}<span>{{ $property->available_from->format('M d, Y') }}</span></li>
                </ul>
            </div>
            <!-- Column -->
            <div class="column col-lg-6 col-md-12 col-sm-12">
                <ul class="propert-info_list">
                    <li>{{ __('Tipe Properti') }}<span>{{ $property->type->label() }}</span></li>
                    <li>{{ __('Garasi') }}<span>{{ $property->garages }}</span></li>
                    <li>{{ __('Furnished') }}<span>{{ $property->furnished ? __('Ya') : __('Tidak') }}</span></li>
                    <li>{{ __('Harga') }}<span>Rp {{ number_format($property->price) }}</span></li>
                    <li>{{ __('Status') }}<span>{{ $property->status->label() }}</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>
