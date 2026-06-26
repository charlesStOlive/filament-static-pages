@props([
    'backgroundDatas' => [],
    'ambiance' => [],
    'anchor' => null,
    'class' => '',
    'mode' => 'front',
])

@php
    $backgroundMode = $backgroundDatas['mode'] ?? 'aucun';
    $backgroundImage = $backgroundMode === 'image' ? $backgroundDatas['image_background'] ?? null : null;
    $coucheBlanc = $backgroundMode === 'image' ? $backgroundDatas['couche_blanc'] ?? 'aucun' : 'aucun';
    $gradients = $backgroundDatas['gradients'] ?? 'aucun';
    $useMask = $backgroundMode === 'filtre';
    $mask = $backgroundMode === 'filtre' ? $backgroundDatas['mask'] ?? '' : '';
    $maskColor = $backgroundMode === 'filtre' ? $backgroundDatas['mask_color'] ?? '' : '';

    $isHidden = $ambiance['is_hidden'] ?? false;
    $minH70vh = $ambiance['minH70vh'] ?? false;
    $separator = $ambiance['afficher_separateur'] ?? false;
    $couleurPrimaire = $ambiance['couleur_primaire'] ?? 'primary';
@endphp

<section @if ($anchor) id="{{ $anchor }}" @endif
    class="{{ $mode === 'preview' ? 'min-h-[120px]' : '' }} relative p-8 md:p-16
        {{ $minH70vh ? 'min-h-[70vh]' : '' }} {{ $class }}
        {{ $backgroundImage ? 'bg-cover bg-center' : 'bg-white' }} flex items-center"
    @if ($backgroundImage) style="background-image: url('{{ $backgroundImage }}')" @endif>
    {{-- Overlay gradients / masques --}}
    <div
        class="absolute inset-0 {{ $gradients }}
        @if ($useMask) {{ $maskColor }} {{ $mask }} @endif">
    </div>

    @if ($coucheBlanc !== 'aucun')
        <div class="absolute inset-0 {{ $coucheBlanc }}"></div>
    @endif

    {{-- Contenu via slot --}}
    <div class="relative z-2 w-full">
        {{ $slot }}
    </div>

    @if ($separator)
        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2">
            <div
                class="w-64 h-1 {{ $couleurPrimaire === 'primary' ? 'bg-primary-500' : 'bg-secondary-500' }} rounded-full">
            </div>
        </div>
    @endif

    @if ($isHidden)
        <div class="absolute inset-0 bg-white/50 flex items-center justify-center z-5">
            <div class="bg-gray-900/80 text-white px-4 py-2 rounded-lg font-semibold">
                Bloc masqué temporairement
            </div>
        </div>
    @endif
</section>
