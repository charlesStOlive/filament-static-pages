<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="description" content="{{ $metaDescription ?? config('app.name') }}">
    <meta property="og:title" content="{{ $title ?? config('app.name') }}">
    <meta property="og:description" content="{{ $metaDescription ?? config('app.name') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <title>{{ $title ?? config('app.name') }}</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    {{-- Adapte les chemins selon ta configuration Vite --}}
    @vite(['resources/css/front/front.css', 'resources/js/front/index.js'])
    @livewireStyles

    @stack('styles')
</head>

<body class="font-sans antialiased bg-white" x-data="frontApp()">

    @include('partials.header')

    <main>
        {{ $slot }}
    </main>

    <!-- Contact Form (conditionnel) -->
    @if (isset($hasForm) && $hasForm)
        @include('partials.blockform')
    @endif

    @include('partials.footer')

    @stack('scripts')
    @livewireScripts
</body>

</html>
