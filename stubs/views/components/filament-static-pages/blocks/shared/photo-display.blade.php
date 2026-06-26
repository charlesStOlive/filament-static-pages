@props([
    'data' => [],
    'class' => '',
])

@php
    $photoUrl = $data['image_url'] ?? null;
    $displayType = $data['display_type'] ?? 'mask_brush_square';
    $position = $data['position'] ?? 'center';

    $displayClasses = match ($displayType) {
        'mask_brush_square' => 'aspect-square w-full max-w-md relative',
        'mask_brush_169' => 'aspect-video w-full max-w-lg relative',
        'full_cover' => 'w-full h-full relative',
        default => 'aspect-square w-full max-w-md relative',
    };

    $positionClasses = match ($position) {
        'center' => 'object-center',
        'top' => 'object-top',
        'bottom' => 'object-bottom',
        'left' => 'object-left',
        'right' => 'object-right',
        default => 'object-center',
    };

    $customPosition = match ($position) {
        'top-left' => 'object-position: left top;',
        'top-right' => 'object-position: right top;',
        'bottom-left' => 'object-position: left bottom;',
        'bottom-right' => 'object-position: right bottom;',
        default => '',
    };

    $imageClasses = match ($displayType) {
        'mask_brush_square' => 'w-full h-full bg-cover bg-center mask-brush',
        'mask_brush_169' => 'w-full h-full bg-cover bg-center mask-brush-169',
        'full_cover' => 'w-full h-full object-cover ' . $positionClasses,
        default => 'w-full h-full bg-cover bg-center mask-brush',
    };
@endphp

@if ($photoUrl)
    <div class="flex justify-center {{ $class }}">
        <div class="{{ $displayClasses }}">
            @if (in_array($displayType, ['mask_brush_square', 'mask_brush_169']))
                <div class="{{ $imageClasses }}" style="background-image: url('{{ $photoUrl }}');"></div>
            @elseif ($displayType === 'full_cover')
                <img src="{{ $photoUrl }}" alt="" class="{{ $imageClasses }}"
                    @if ($customPosition) style="{{ $customPosition }}" @endif>
            @endif
        </div>
    </div>
@endif
