@props(['block', 'mode' => 'front', 'page' => null])

@php
    $data            = \CharlesStOlive\FilamentStaticPages\Support\BlockDataParser::fromBlockData($block['data'] ?? [], $mode, $page);
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
            />
        @endif

        <x-filament-static-pages.blocks.shared.button-group
            :boutons="$data['boutons'] ?? []"
            class="fade-in-up"
        />
    </div>
</x-filament-static-pages.blocks.shared.section>
