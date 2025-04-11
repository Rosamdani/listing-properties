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
        function initGoogleMaps() {
            if (typeof window.initAutocompleteHeader === "function") {
                console.log('initAutocompleteHeader called');
                window.initAutocompleteHeader();
            }

            if (typeof window.initMap === "function") {
                console.log('initMap called');
                window.initMap(); // ganti dari window.initLivewireMap()
            }
        }
    </script>
    @stack('scripts')
</html>
