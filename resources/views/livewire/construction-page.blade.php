@php
    $settingsClass = config('filament-static-pages.settings.class');
    $settings = app($settingsClass);
@endphp

<div class="bg-white p-8 rounded-lg shadow-lg text-center max-w-md w-full">
    {{-- Logo si disponible --}}
    @if($settings->logo)
        <div class="mb-6">
            <img src="{{ Storage::url($settings->logo) }}" alt="Logo" class="h-16 mx-auto">
        </div>
    @endif

    {{-- Titre de maintenance --}}
    <h1 class="text-2xl font-bold text-gray-800 mb-4">
        {{ $settings->construction['titre'] ?? 'Site en maintenance' }}
    </h1>

    {{-- Description --}}
    <p class="text-gray-600 mb-6 leading-relaxed">
        {{ $settings->construction['description'] ?? 'Nous travaillons actuellement sur notre site.' }}
    </p>

    {{-- Informations de contact --}}
    <div class="text-sm text-gray-500 space-y-1">
        <p class="font-medium">Nous contacter :</p>
        <p>{{ $settings->email }}</p>
        <p>{{ $settings->telephone }}</p>
    </div>
</div>
