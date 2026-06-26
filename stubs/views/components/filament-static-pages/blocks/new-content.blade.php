@props(['block', 'mode' => 'front', 'page' => null])

@php
    $data            = \CharlesStOlive\FilamentStaticPages\Support\BlockDataParser::fromBlockData($block['data'] ?? [], $mode, $page);
    $ambiance        = $data['ambiance'] ?? [];
    $backgroundDatas = $data['background_datas'] ?? [];
    $subcontents     = $data['subcontents'] ?? [];
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

        @if (! empty($subcontents))
            <div class="space-y-14">
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
        @endif
    </div>
</x-filament-static-pages.blocks.shared.section>
