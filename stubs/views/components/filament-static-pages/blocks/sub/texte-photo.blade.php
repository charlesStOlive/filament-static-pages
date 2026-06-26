{{--
    Variables reçues via @include :
    - $subData          : données traitées du sous-bloc
    - $couleurPrimaire  : hérité du bloc parent
    - $styleListes      : hérité du bloc parent
--}}
@php
    $photo = $subData['photo_config'] ?? [];
    $image = $photo['image_url'] ?? null;
    $hasImage = filled($image);
@endphp

<div class="{{ $hasImage ? 'grid md:grid-cols-2 gap-12 items-center' : 'max-w-4xl mx-auto' }}">
    <x-filament-static-pages.blocks.shared.html-reader :content="$subData['html_texts'] ?? null" :couleur-primaire="$couleurPrimaire" :style-listes="$styleListes"
        class="fade-in-left md:order-1 max-w-4xl" />

    @if ($hasImage)
        <x-filament-static-pages.blocks.shared.photo-display :data="$photo" class="fade-in-right md:order-2" />
    @endif
</div>
