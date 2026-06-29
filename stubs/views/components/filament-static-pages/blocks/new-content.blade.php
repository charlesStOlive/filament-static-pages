@props(['block' => [], 'mode' => 'front', 'page' => null])

@php


    // ── Détection du mode d'affichage ─────────────────────────────────────────
    // MODE FRONT  : $block['data'] contient les données (Livewire static-page).
    // MODE PREVIEW: Filament Builder passe les champs comme variables Blade
    //               individuelles → $block est vide.
    $rawData = $block['data'] ?? [];

    if (empty($rawData)) {
        // En preview, les sous-blocs ne sont pas prévisualisables individuellement
        // → on affiche le conteneur principal avec un placeholder
        $data = \CharlesStOlive\FilamentStaticPages\Support\BlockDataParser::extractDataFromBladeVars(get_defined_vars());
        $mode = 'preview';
        $subcontents = [];
    } else {
        $data = \CharlesStOlive\FilamentStaticPages\Support\BlockDataParser::fromBlockData($rawData, $mode, $page);
        // subcontents est une liste (array indexé), pas traité par fromBlockData
        // Chaque sous-bloc est traité individuellement dans la boucle ci-dessous
        $subcontents = $block['data']['subcontents'] ?? [];
    }

    $ambiance        = $data['ambiance'] ?? [];
    $backgroundDatas = $data['background_datas'] ?? [];
    $couleurPrimaire = $ambiance['couleur_primaire'] ?? 'secondary';
    $styleListes     = $ambiance['style_listes'] ?? 'alternance';
@endphp

<x-filament-static-pages.blocks.shared.section
    :backgroundDatas="$backgroundDatas"
    :ambiance="$ambiance"
    :anchor="$data['anchor'] ?? ''"
    :mode="$mode"
>
    <div class="mx-auto relative z-2 max-w-7xl px-4 sm:px-6 lg:px-8">
        @if (($data['html_title'] ?? null) || ($data['description'] ?? null))
            <div class="text-center mb-16">
                @if ($data['html_title'] ?? null)
                    <x-filament-static-pages.blocks.shared.title
                        :title="$data['html_title']"
                        :couleur-primaire="$couleurPrimaire"
                    />
                @endif

                @if ($data['description'] ?? null)
                    <x-filament-static-pages.blocks.shared.description
                        :description="$data['description']"
                    />
                @endif
            </div>
        @endif

        @if ($mode === 'preview' && empty($subcontents))
            {{-- Placeholder visible uniquement dans la prévisualisation Filament --}}
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center text-gray-400 text-sm">
                Les sous-blocs apparaissent ici en mode front
            </div>
        @endif

        @foreach ($subcontents as $subBlock)
            @php
                $subType = $subBlock['type'] ?? null;
                $subData = \CharlesStOlive\FilamentStaticPages\Support\BlockDataParser::fromBlockData(
                    $subBlock['data'] ?? [],
                    $mode,
                    $page,
                );
                $subView = $subType
                    ? \CharlesStOlive\FilamentStaticPages\Blocks\SubBlockRegistry::viewFor($subType)
                    : null;
            @endphp

            @if (! $subView)
                @continue
            @endif

            <div class="{{ ! $loop->last ? 'pb-14 border-b border-gray-200' : '' }}">
                @include($subView, [
                    'subData'         => $subData,
                    'couleurPrimaire' => $couleurPrimaire,
                    'styleListes'     => $styleListes,
                ])
            </div>
        @endforeach
    </div>
</x-filament-static-pages.blocks.shared.section>
