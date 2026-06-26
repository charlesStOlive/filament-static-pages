@props([
    'content' => null,
    'couleurPrimaire' => 'secondary',
    'styleListes' => 'alternance',
    'class' => '',
])

<div
    class="prose prose-ul:my-0 prose-ul:py-1 prose-li:my-1 prose-li:py-0 prose-li:marker:text-2xl
    prose-ol:my-0 prose-ol:py-1 prose-ol:li:marker:text-2xl
    {{ $couleurPrimaire === 'primary' ? 'prose-a:text-primary-500 prose-blockquote:border-l-primary-500' : 'prose-a:text-secondary-500 prose-blockquote:border-l-secondary-500' }}
    @if ($styleListes === 'primary') prose-li:marker:text-primary-500 prose-ol:li:marker:text-primary-500
    @elseif ($styleListes === 'secondary') prose-li:marker:text-secondary-500 prose-ol:li:marker:text-secondary-500
    @else prose-li:odd:marker:text-primary-500 prose-li:even:marker:text-secondary-500
        prose-ol:li:nth-child(odd):marker:text-primary-500 prose-ol:li:nth-child(even):marker:text-secondary-500 @endif
    {{ $class }}">
    {!! $content !!}
</div>
