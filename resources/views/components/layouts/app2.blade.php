<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <x-layouts.styles />
        {!! seo() !!}
        <title>{{ $title ?? 'Page Title' }}</title>
        @stack('styles')
        @livewireStyles
    </head>
    <body style="background-color: #faf9f8; height: 100vh; overflow: hidden;">
        <!-- Preloader -->
        <div class="preloader">
            <div class="box"></div>
        </div>

        <x-layouts.header2 />

        {{ $slot }}
    </body>
    @livewireScripts
    <x-wrapper.progress />
    <x-layouts.scripts />
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_API_KEY') }}&libraries=places&callback=initGoogleMaps">
    </script>
    <script>
         function initAutocompleteHeader() {
            const input = document.getElementById('autocompleteMap');
            if (!input) {
                console.error('Element dengan ID "autocompleteMap" tidak ditemukan');
                return;
            }
            
            const autocomplete = new google.maps.places.Autocomplete(input, {
                fields: ["place_id", "geometry", "name", "formatted_address"]
            });
            
            autocomplete.addListener('place_changed', function () {
                const place = autocomplete.getPlace();
                
                if (!place.geometry) {
                    input.placeholder = 'Enter a place';
                } else {
                    if (typeof Livewire !== 'undefined') {
                        Livewire.emit('placeSelected', {
                            lat: place.geometry.location.lat(),
                            lng: place.geometry.location.lng(),
                            name: place.name,
                            address: place.formatted_address
                        });
                    } else {
                        console.error('Livewire tidak ditemukan');
                    }
                }
            });
            
            console.log('Autocomplete berhasil diinisialisasi');
        }
        
        function initMap() {
            var uluru = {lat: -6.21462, lng: 106.84513};
            var mapElement = document.getElementById('map');
            
            if (!mapElement) {
                console.error('Element dengan ID "map" tidak ditemukan');
                return;
            }
            
            var map = new google.maps.Map(mapElement, {
                zoom: 12,
                center: uluru
            });
            
            var marker = new google.maps.Marker({
                position: uluru,
                map: map
            });
            
            console.log('Map berhasil diinisialisasi');
        }
        
        function initGoogleMaps() {
            console.log('Google Maps API dimuat, memulai inisialisasi...');
            initAutocompleteHeader();
            initMap();
        }
    </script>
    @stack('scripts')
</html>
