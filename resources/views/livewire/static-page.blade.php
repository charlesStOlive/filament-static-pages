@php
    $registry = app(\Notilac\FilamentStaticPages\Blocks\PageBlockRegistry::class);
@endphp

<div>
    @if($page->contents)
        @foreach($page->contents as $block)
            @php
                $isHidden = $block['data']['is_hidden'] ?? false;
                $component = $registry->componentFor($block['type'] ?? '');
            @endphp

            @continue($isHidden)
            @continue(! $component)

            <x-dynamic-component
                :component="$component"
                :block="$block"
                :page="$page"
                mode="front"
            />
        @endforeach
    @else
        <div class="text-gray-500">
            Aucun contenu défini pour cette page.
        </div>
    @endif
</div>