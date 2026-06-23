@props([
    'block',
    'mode' => 'front',
    'page' => null,
])

@php
    $rawData = $block['data'] ?? [];

    $data = \Notilac\FilamentStaticPages\Support\BlockDataParser::fromBlockData(
        $rawData,
        $mode,
        $page
    );

    $ambiance = $data['ambiance'] ?? [];
    $subcontents = $data['subcontents'] ?? [];
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

        @if(! empty($subcontents))
            <div class="space-y-14">
                @foreach($subcontents as $subBlock)
                    @php
                        $subType = $subBlock['type'] ?? null;

                        $subData = \Notilac\FilamentStaticPages\Support\BlockDataParser::fromBlockData(
                            $subBlock['data'] ?? [],
                            $mode,
                            $page
                        );

                        $isHidden = (bool) ($subData['is_hidden'] ?? false);

                        $photo = $subData['photo_config'] ?? [];
                        $image = $photo['image_url'] ?? null;
                        $hasImage = filled($image);
                    @endphp

                    @continue($isHidden)

                    <div
                        @if($subData['anchor'] ?? null) id="{{ $subData['anchor'] }}" @endif
                        class="{{ ! $loop->last ? 'pb-14 border-b border-gray-200' : '' }}"
                    >
                        @if($subType === 'texte-photo')
                            <div class="{{ $hasImage ? 'grid md:grid-cols-2 gap-10 items-center' : 'max-w-4xl mx-auto' }}">
                                <div class="prose max-w-none">
                                    {!! $subData['html_texts'] ?? '' !!}
                                </div>

                                @if($hasImage)
                                    <div>
                                        <img
                                            src="{{ $image }}"
                                            alt=""
                                            class="w-full rounded-xl object-cover"
                                        >
                                    </div>
                                @endif
                            </div>
                        @elseif($subType === 'photo-texte')
                            <div class="{{ $hasImage ? 'grid md:grid-cols-2 gap-10 items-center' : 'max-w-4xl mx-auto' }}">
                                @if($hasImage)
                                    <div>
                                        <img
                                            src="{{ $image }}"
                                            alt=""
                                            class="w-full rounded-xl object-cover"
                                        >
                                    </div>
                                @endif

                                <div class="prose max-w-none">
                                    {!! $subData['html_texts'] ?? '' !!}
                                </div>
                            </div>
                        @elseif($subType === 'texte-texte')
                            <div class="grid md:grid-cols-2 gap-10 items-start">
                                <div class="prose max-w-none">
                                    {!! $subData['html_texts'] ?? '' !!}
                                </div>

                                <div class="prose max-w-none">
                                    {!! $subData['html_secondary_text'] ?? '' !!}
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        @if($ambiance['afficher_separateur'] ?? false)
            <div class="mt-12 flex justify-center">
                <div class="h-1 w-24 rounded-full bg-primary-600"></div>
            </div>
        @endif
    </div>
</section>