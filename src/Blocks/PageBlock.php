<?php

namespace CharlesStOlive\FilamentStaticPages\Blocks;

use Filament\Forms\Components\Builder\Block;

abstract class PageBlock
{
    abstract public static function type(): string;

    abstract public static function label(): string;

    abstract public static function schema(): array;

    public static function component(): string
    {
        // Used with <x-dynamic-component>, resolved via anonymous component path ('static-pages' → resources/views/components)
        return 'static-pages::blocks.' . static::type();
    }

    public static function preview(): ?string
    {
        // Used with view() in renderPreview(), resolved via 'filament-static-pages' namespace (→ resources/views)
        return 'filament-static-pages::components.blocks.' . static::type();
    }

    public static function filamentBlock(): Block
    {
        return StaticPageBlock::make(static::type())
            ->label(static::label())
            ->label(function (?array $state): string {
                $label = mb_strtoupper(static::label());

                if ($state === null) {
                    return $label;
                }

                $anchor = $state['anchor'] ?? 'X';

                return sprintf('%s | Ancre : [%s]', $label, $anchor);
            })
            ->schema(static::schema())
            ->preview(static::preview());
    }
}
