@props([
    'boutons' => [],
    'class' => '',
])

@if (!empty($boutons))
    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center {{ $class }}">
        @foreach ($boutons as $bouton)
            @php
                $routeName = config('filament-static-pages.route.name', 'page');

                $href = match ($bouton['type_lien'] ?? 'externe') {
                    'page' => !isset($bouton['page_id']) || $bouton['page_id'] === 'same_page'
                        ? $bouton['ancre'] ?? '#'
                        : route($routeName, ['slug' => $bouton['page_id']]) . ($bouton['ancre'] ?? ''),
                    'externe' => $bouton['url_externe'] ?? '#',
                    default => '#',
                };

                $target = $bouton['nouvel_onglet'] ?? false ? '_blank' : '_self';
                $rel = $bouton['nouvel_onglet'] ?? false ? 'noopener noreferrer' : '';
            @endphp

            <a href="{{ $href }}"
                @if ($target === '_blank') target="_blank" rel="noopener noreferrer" @endif
                class="btn-base text-white bg-{{ $bouton['couleur'] ?? 'primary' }}-500">
                {{ $bouton['texte'] ?? 'Bouton' }}
            </a>
        @endforeach
    </div>
@endif
