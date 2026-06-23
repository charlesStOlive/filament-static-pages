@props(['block', 'mode' => 'front', 'page' => null])

@php
    $rawData = $block['data'] ?? [];

    $data = \Notilac\FilamentStaticPages\Support\BlockDataParser::fromBlockData($rawData, $mode, $page);

    $ambiance = $data['ambiance'] ?? [];
@endphp

<section @if ($data['anchor'] ?? null) id="{{ $data['anchor'] }}" @endif
    class="py-24 {{ $ambiance['minH70vh'] ?? false ? 'min-h-[70vh]' : '' }}">
    <div class="max-w-7xl mx-auto px-4 text-center">
        @if ($data['html_title'] ?? null)
            <div class="prose prose-lg md:prose-xl max-w-4xl mx-auto">
                {!! $data['html_title'] !!}
            </div>
        @endif

        @if ($data['description'] ?? null)
            <p class="mt-6 max-w-3xl mx-auto text-lg text-gray-700">
                {{ $data['description'] }}
            </p>
        @endif

        @if (!empty($data['boutons']))
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                @foreach ($data['boutons'] as $button)
                    <a href="{{ $button['url_externe'] ?? '#' }}"
                        class="inline-flex rounded-md px-4 py-2 bg-primary-600 text-white">
                        {{ $button['texte'] ?? 'Voir' }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
