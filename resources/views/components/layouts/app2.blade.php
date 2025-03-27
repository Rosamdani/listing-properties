<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <x-layouts.styles />
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
    @stack('scripts')
</html>
