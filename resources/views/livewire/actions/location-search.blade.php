<div class="property-search-form" x-data="{ initGeolocation() { this.$wire.useCurrentLocation(); this.getLocation(); }, getLocation() { if (navigator.geolocation) { navigator.geolocation.getCurrentPosition(position => { const lat = position.coords.latitude; const lng = position.coords.longitude; // Pada implementasi nyata, Anda sebaiknya melakukan reverse geocoding dengan API // seperti Google Maps API untuk mendapatkan alamat berdasarkan koordinat const address = `Lokasi Saat Ini (${lat.toFixed(4)}, ${lng.toFixed(4)})`; this.$wire.setCurrentLocation(address, lat, lng); }); } } }">
    <div class="form-group position-relative">
        <div class="input-group border rounded-pill overflow-hidden">
            <div class="input-group-prepend">
                <span class="input-group-text bg-white border-0">
                    <i class="flaticon-search text-primary"></i>
                </span>
            </div>
            <input 
                id="autocompleteMap"
                type="text" 
                class="form-control border-0 search-input border-0 py-3"
                style="box-shadow: none;" 
                placeholder="{{ __('Cari lokasi, area, atau alamat') }}" 
            >
        </div>
    </div>
</div>

@push('scripts')
    <script>
    window.initAutocompleteHeader = function () {
        const input = document.getElementById('autocomplete');
        if (!input) return;

        const autocomplete = new google.maps.places.Autocomplete(input, {
            fields: ["place_id", "geometry", "name", "formatted_address"]
        });

        autocomplete.addListener('place_changed', function () {
            const place = autocomplete.getPlace();

            if (!place.geometry) {
                input.placeholder = 'Enter a place';
            } else {
                Livewire.emit('placeSelected', {
                    lat: place.geometry.location.lat(),
                    lng: place.geometry.location.lng(),
                    name: place.name,
                    address: place.formatted_address
                });
            }
        });
    }
</script>

@endpush