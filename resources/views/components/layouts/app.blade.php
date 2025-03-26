<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <x-layouts.styles />
        <title>{{ $title ?? 'Page Title' }}</title>
        @livewireStyles
    </head>
    <body>
        <!-- Preloader -->
        <div class="preloader">
            <div class="box"></div>
        </div>
        
        @if (request()->routeIs('buy') || request()->routeIs('rent'))
        <x-layouts.header2 />
        @else
        <x-layouts.header />
        @endif

        {{ $slot }}
    </body>
    @livewireScripts
    <x-wrapper.progress />
    <x-layouts.scripts />
    @stack('scripts')
</html>
