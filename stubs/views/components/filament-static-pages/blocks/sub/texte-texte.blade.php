{{--
    Variables reçues via @include :
    - $subData          : données traitées du sous-bloc
    - $couleurPrimaire  : hérité du bloc parent
    - $styleListes      : hérité du bloc parent
--}}
<div class="grid md:grid-cols-2 gap-12 items-start">
    <x-filament-static-pages.blocks.shared.html-reader :content="$subData['html_texts'] ?? null" :couleur-primaire="$couleurPrimaire" :style-listes="$styleListes"
        class="fade-in-left max-w-4xl" />

    <x-filament-static-pages.blocks.shared.html-reader :content="$subData['html_secondary_text'] ?? null" :couleur-primaire="$couleurPrimaire" :style-listes="$styleListes"
        class="fade-in-right max-w-4xl" />
</div>
