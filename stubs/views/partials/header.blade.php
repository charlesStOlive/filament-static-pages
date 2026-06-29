<header class="w-full bg-white shadow-sm">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        {{-- Logo --}}
        <a href="/" class="flex items-center">
            @php $settings = app(\App\Settings\AdminSettings::class); @endphp
            @if ($settings->logo)
                <img src="{{ Storage::url($settings->logo) }}" alt="{{ config('app.name') }}" class="h-10">
            @else
                <span class="font-bold text-xl text-primary-600">{{ config('app.name') }}</span>
            @endif
        </a>

        {{-- Navigation principale — à personnaliser --}}
        <div class="hidden md:flex items-center space-x-6">
            {{-- <a href="/" class="text-gray-600 hover:text-primary-600">Accueil</a> --}}
        </div>
    </nav>
</header>
