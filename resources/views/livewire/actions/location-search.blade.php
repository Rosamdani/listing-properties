<div class="property-search-form" x-data="{ initGeolocation() { this.$wire.useCurrentLocation(); this.getLocation(); }, getLocation() { if (navigator.geolocation) { navigator.geolocation.getCurrentPosition(position => { const lat = position.coords.latitude; const lng = position.coords.longitude; // Pada implementasi nyata, Anda sebaiknya melakukan reverse geocoding dengan API // seperti Google Maps API untuk mendapatkan alamat berdasarkan koordinat const address = `Lokasi Saat Ini (${lat.toFixed(4)}, ${lng.toFixed(4)})`; this.$wire.setCurrentLocation(address, lat, lng); }); } } }">
    <div class="form-group position-relative">
        <div class="input-group border rounded-pill overflow-hidden">
            <div class="input-group-prepend">
                <span class="input-group-text bg-white border-0">
                    <i class="flaticon-search text-primary"></i>
                </span>
            </div>
            <input 
                type="text" 
                class="form-control border-0 search-input border-0 py-3"
                style="box-shadow: none;" 
                placeholder="{{ __('Cari lokasi, area, atau alamat') }}" 
                wire:model.debounce.300ms="search"
                wire:click="toggleDropdown"
                wire:keydown.escape="$set('showDropdown', false)"
            >
            @if($selectedLocation)
                <div class="input-group-append">
                    <button type="button" class="btn bg-white border-0" wire:click="resetLocation">
                        <i class="flaticon-close-1"></i>
                    </button>
                </div>
            @endif
        </div>
        
        <!-- Location Dropdown -->
        @if($showDropdown)
            <div class="location-dropdown shadow-lg position-absolute w-100 bg-white rounded mt-1 z-50">
                <!-- Current Location Option -->
                <div class="p-2 border-bottom">
                    <a href="#" class="d-flex align-items-center text-primary p-2 rounded hover-bg-light" @click.prevent="initGeolocation()">
                        @if($showCurrentLocationLoader)
                            <div class="spinner-border spinner-border-sm text-primary mr-2" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        @else
                            <i class="flaticon-placeholder mr-2"></i>
                        @endif
                        <span>{{ __('Gunakan Lokasi Saat Ini') }}</span>
                    </a>
                </div>
                
                <!-- Popular/Search Results -->
                <div class="p-2 bg-light">
                    <small class="text-muted">
                        {{ count($searchResults) > 0 && strlen($search) >= 2 ? __('Hasil Pencarian') : __('Lokasi Populer') }}
                    </small>
                </div>
                
                <div class="location-results p-0">
                    @if(count($searchResults) > 0 || (strlen($search) < 2 && count($popularLocations) > 0))
                        <ul class="list-unstyled m-0">
                            @foreach((strlen($search) >= 2 ? $searchResults : $popularLocations) as $location)
                                <li class="location-item p-2 border-bottom" wire:key="location-{{ $location['id'] }}">
                                    <a href="#" class="d-flex align-items-center text-decoration-none text-dark p-1 rounded hover-bg-light"
                                       wire:click.prevent="selectLocation('{{ $location['id'] }}', '{{ $location['name'] }}', '{{ $location['type'] }}')">
                                        <i class="flaticon-placeholder text-primary mr-2"></i>
                                        <div>
                                            <div>{{ $location['name'] }}</div>
                                            <small class="text-muted">{{ $location['type'] === 'city' ? __('Kota') : __('Area') }}</small>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="p-3 text-center text-muted">
                            <small>{{ __('Tidak ada hasil ditemukan') }}</small>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>