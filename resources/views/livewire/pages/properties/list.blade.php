<?php

use App\Enum\PropertyStatus;
use App\Enum\PropertyType;
use App\Models\Properties;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Volt\Component;

new class extends Component {
    use WithPagination;

    #[Url]
    public $search = '';
    
    #[Url]
    public $type = '';
    
    #[Url]
    public $status = '';
    
    #[Url]
    public $minPrice = '';
    
    #[Url]
    public $maxPrice = '';
    
    #[Url]
    public $bedrooms = '';
    
    #[Url]
    public $location = ''; // Variabel untuk filter lokasi
    
    // Gunakan listeners dengan format Volt yang benar untuk Livewire v3
    public function boot()
    {
        $this->listeners = [
            'locationChanged' => 'updateLocation',
            'filterUpdated' => 'handleFilterUpdate'
        ];
    }
    
    public function updateLocation($location)
    {
        // Update lokasi berdasarkan event dari komponen peta
        $this->location = $location;
        $this->resetPage();
    }
    
    public function handleFilterUpdate($field, $value)
    {
        // Log untuk debugging
        \Illuminate\Support\Facades\Log::info("List: Filter diperbarui: {$field} = " . json_encode($value));
        
        // Update nilai filter
        $this->{$field} = $value;
        
        // Reset halaman pagination
        $this->resetPage();
    }

    public function getPropertyTypesProperty()
    {
        return PropertyType::cases();
    }

    public function getPropertyStatusesProperty()
    {
        return PropertyStatus::cases();
    }

    public function getPropertiesProperty()
    {
        return Properties::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('location', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->type, function ($query) {
                $query->where('type', $this->type);
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->minPrice, function ($query) {
                $query->where('price', '>=', $this->minPrice);
            })
            ->when($this->maxPrice, function ($query) {
                $query->where('price', '<=', $this->maxPrice);
            })
            ->when($this->bedrooms, function ($query) {
                $query->where('bedrooms', '>=', $this->bedrooms);
            })
            ->when($this->location, function ($query) {
                // Coba cari properti dengan lokasi yang cocok
                $query->where(function($q) {
                    // Cari di lokasi langsung
                    $q->where('location', 'like', '%' . $this->location . '%')
                      // Atau cari di provinsi (bagian terakhir dari lokasi)
                      ->orWhereRaw('LOWER(TRIM(SUBSTRING_INDEX(location, ",", -1))) LIKE ?', ['%' . strtolower(trim($this->location)) . '%']);
                });
            })
            ->latest()
            ->paginate(6);
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->type = '';
        $this->status = '';
        $this->minPrice = '';
        $this->maxPrice = '';
        $this->bedrooms = '';
        $this->location = '';
        $this->resetPage();
    }
    
    // Menambahkan metode untuk update ketika nilai berubah
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function updatedType()
    {
        $this->resetPage();
    }
    
    public function updatedStatus()
    {
        $this->resetPage();
    }
    
    public function updatedMinPrice()
    {
        $this->resetPage();
    }
    
    public function updatedMaxPrice()
    {
        $this->resetPage();
    }
    
    public function updatedBedrooms()
    {
        $this->resetPage();
    }
    
    public function updatedLocation()
    {
        $this->resetPage();
    }
}; ?>

<div class="auto-container">
    <!-- Filter Section -->
    <div class="mb-5">
        <div class="row clearfix">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Search</label>
                    <input type="text" wire:model.live="search" class="form-control" placeholder="Search properties...">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Type</label>
                    <select wire:model.live="type" class="form-control">
                        <option value="">All Types</option>
                        @foreach($this->propertyTypes as $propertyType)
                            <option value="{{ $propertyType->value }}">{{ $propertyType->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Status</label>
                    <select wire:model.live="status" class="form-control">
                        <option value="">All Statuses</option>
                        @foreach($this->propertyStatuses as $propertyStatus)
                            <option value="{{ $propertyStatus->value }}">{{ $propertyStatus->label() }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row clearfix mt-3">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Min Price</label>
                    <input type="number" wire:model.live="minPrice" class="form-control" placeholder="Min Price">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Max Price</label>
                    <input type="number" wire:model.live="maxPrice" class="form-control" placeholder="Max Price">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Bedrooms</label>
                    <select wire:model.live="bedrooms" class="form-control">
                        <option value="">Any</option>
                        <option value="1">1+</option>
                        <option value="2">2+</option>
                        <option value="3">3+</option>
                        <option value="4">4+</option>
                        <option value="5">5+</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button wire:click="resetFilters" class="btn btn-primary d-block w-100">Reset Filters</button>
                </div>
            </div>
        </div>
        
        {{-- Tampilkan filter lokasi jika aktif --}}
        @if($location)
        <div class="row clearfix mt-3">
            <div class="col-md-12">
                <div class="alert alert-info d-flex justify-content-between align-items-center">
                    <span>Showing properties in: <strong>{{ $location }}</strong></span>
                    <button wire:click="$set('location', '')" class="btn btn-sm btn-outline-secondary">Clear Location Filter</button>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="row clearfix">
        @forelse($this->properties as $property)
            <div class="property-block_one style-three col-lg-4 col-md-6 col-sm-12">
                <div class="property-block_one-inner wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
                    <div class="property-block_one-image">
                        @if($property->featured)
                            <div class="property-block_one-title">Featured</div>
                        @endif
                        <a href="{{ route('properties.show', $property) }}">
                            <img src="{{ $property->thumbnail }}" alt="{{ $property->name }}" />
                        </a>
                    </div>
                    <div class="property-block_one-content">
                        <div class="property-block_one-location">
                            <i class="flaticon-maps-and-flags"></i>{{ $property->location }}
                        </div>
                        <h4 class="property-block_one-heading">
                            <a href="{{ route('properties.show', $property) }}">{{ $property->name }}</a>
                        </h4>
                        <ul class="property-block_one-info">
                            <li><span><img src="{{ asset('assets/images/icons/bed.svg') }}" alt="" /></span>{{ $property->bedrooms }} Beds</li>
                            <li><span><img src="{{ asset('assets/images/icons/bath.svg') }}" alt="" /></span>{{ $property->bathrooms }} Bathrooms</li>
                            <li><span><img src="{{ asset('assets/images/icons/square.svg') }}" alt="" /></span>{{ $property->formatted_area }}</li>
                        </ul>
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div class="property-block_one-price">
                                {{ $property->formatted_price }} 
                                @if($property->status === \App\Enum\PropertyStatus::FOR_RENT)
                                    <span>/month</span>
                                @endif
                            </div>
                            <a class="property-block_one-heart" href="{{ route('properties.show', $property) }}">
                                <img src="{{ asset('assets/images/icons/heart.svg') }}" alt="" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No properties found. Try adjusting your filters.
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="mt-5">
        {{ $this->properties->links('pagination::bootstrap-5') }}
    </div>
</div>