@props(['block' => [], 'mode' => 'front', 'page' => null])

@php


    // ── Détection du mode d'affichage ─────────────────────────────────────────
    // MODE FRONT  : $block['data'] contient les données (Livewire static-page).
    // MODE PREVIEW: Filament Builder passe les champs comme variables Blade
    //               individuelles → $block est vide.
    $rawData = $block['data'] ?? [];

    if (empty($rawData)) {
        // Capture les variables Blade AVANT d'en définir de nouvelles.
        // extractDataFromBladeVars() exclut les vars système (__env, app…)
        // et traite images/html en mode preview (temporaryUrl, etc.)
        $data = \CharlesStOlive\FilamentStaticPages\Support\BlockDataParser::extractDataFromBladeVars(get_defined_vars());
        $mode = 'preview';
    } else {
        // Traitement normal : image_* → URLs Storage, html_* → HTML rendu
        $data = \CharlesStOlive\FilamentStaticPages\Support\BlockDataParser::fromBlockData($rawData, $mode, $page);
    }

    $ambiance        = $data['ambiance'] ?? [];
    $backgroundDatas = $data['background_datas'] ?? [];
@endphp

<x-filament-static-pages.blocks.shared.section
    :backgroundDatas="$backgroundDatas"
    :ambiance="$ambiance"
    :anchor="$data['anchor'] ?? ''"
    :mode="$mode"
>
    <div class="max-w-7xl mx-auto flex flex-col space-y-12 justify-center text-center">
        @if ($data['html_title'] ?? null)
            <x-filament-static-pages.blocks.shared.title
                :title="$data['html_title']"
                :couleur-primaire="$ambiance['couleur_primaire'] ?? 'secondary'"
                :isH1="true"
                class="fade-in-up"
            />
        @endif

        @if ($data['description'] ?? null)
            <x-filament-static-pages.blocks.shared.description
                :description="$data['description']"
                class="fade-in-up"
                data-animation-delay="200"
            />
        @endif

        <x-filament-static-pages.blocks.shared.button-group
            :boutons="$data['boutons'] ?? []"
            class="fade-in-up"
            data-animation-delay="400"
        />
    </div>
</x-filament-static-pages.blocks.shared.section>
