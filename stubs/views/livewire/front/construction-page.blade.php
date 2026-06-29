@php
    $settings = app(\App\Settings\AdminSettings::class);
@endphp

<div class="bg-white p-8 rounded-lg shadow-lg text-center max-w-md w-full">
    @if ($settings->logo)
        <div class="mb-6">
            <img src="{{ Storage::url($settings->logo) }}" alt="Logo" class="h-16 mx-auto">
        </div>
    @endif

    <h1 class="text-2xl font-bold text-gray-800 mb-4">
        {{ $settings->construction['titre'] ?? 'Site en maintenance' }}
    </h1>

    <p class="text-gray-600 mb-6 leading-relaxed">
        {{ $settings->construction['description'] ?? 'Nous travaillons actuellement sur notre site.' }}
    </p>

    <div class="text-sm text-gray-500 space-y-1">
        <p class="font-medium">Nous contacter :</p>
        <p>{{ $settings->email }}</p>
        <p>{{ $settings->telephone }}</p>
    </div>
</div>
