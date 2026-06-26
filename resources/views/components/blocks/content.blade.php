@props([
    'block',
    'mode' => 'front',
    'page' => null,
])

@php
    $rawData = $block['data'] ?? [];

    $data = \CharlesStOlive\FilamentStaticPages\Support\BlockDataParser::fromBlockData(
        $rawData,
        $mode,
        $page
    );

    $ambiance = $data['ambiance'] ?? [];
    $photo = $data['photo_config'] ?? [];

    $image = $photo['image_url'] ?? null;
    $hasImage = filled($image);

    $leftImage = (bool) ($data['left_image'] ?? false);

    $textOrder = $leftImage && $hasImage ? 'md:order-2' : 'md:order-1';
    $imageOrder = $leftImage && $hasImage ? 'md:order-1' : 'md:order-2';
@endphp

<section
    @if($data['anchor'] ?? null) id="{{ $data['anchor'] }}" @endif
    class="py-20 {{ ($ambiance['minH70vh'] ?? false) ? 'min-h-[70vh]' : '' }}"
>
    <div class="max-w-7xl mx-auto px-4">
        @if(($data['html_title'] ?? null) || ($data['description'] ?? null))
            <div class="max-w-4xl mx-auto text-center mb-12">
                @if($data['html_title'] ?? null)
                    <div class="prose prose-lg md:prose-xl max-w-none">
                        {!! $data['html_title'] !!}
                    </div>
                @endif

                @if($data['description'] ?? null)
                    <p class="mt-6 text-lg text-gray-700">
                        {{ $data['description'] }}
                    </p>
                @endif
            </div>
        @endif

        <div class="{{ $hasImage ? 'grid md:grid-cols-2 gap-10 items-center' : 'max-w-4xl mx-auto' }}">
            <div class="prose max-w-none {{ $textOrder }}">
                {!! $data['html_texts'] ?? '' !!}
            </div>

            @if($hasImage)
                <div class="{{ $imageOrder }}">
                    <img
                        src="{{ $image }}"
                        alt=""
                        class="w-full rounded-xl object-cover"
                    >
                </div>
            @endif
        </div>

        @if($ambiance['afficher_separateur'] ?? false)
            <div class="mt-12 flex justify-center">
                <div class="h-1 w-24 rounded-full bg-primary-600"></div>
            </div>
        @endif
    </div>
</section>