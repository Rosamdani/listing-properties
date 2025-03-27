<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <x-layouts.styles />
        <title>{{ $title ?? 'Page Title' }}</title>
        @livewireStyles
    </head>
    <body style="background-color: #faf9f8;">
        <!-- Preloader -->
        <div class="preloader">
            <div class="box"></div>
        </div>

        <x-layouts.header />

        {{ $slot }}
    </body>
    @livewireScripts
    <x-wrapper.progress />
    <x-layouts.scripts />
    @stack('scripts')
</html>
