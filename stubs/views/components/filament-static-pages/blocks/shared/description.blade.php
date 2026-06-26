@props([
    'description' => null,
    'class' => '',
])

@if ($description)
    <div class="text-center {{ $class }}">
        <div class="text-lg md:text-xl text-gray-600 mx-auto max-w-4xl whitespace-break-spaces">
            {{ $description }}
        </div>
    </div>
@endif
