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
        // Resolves to resources/views/components/filament-static-pages/blocks/{type}.blade.php
        // Published by: php artisan filament-static-pages:install
        return 'filament-static-pages.blocks.' . static::type();
    }

    public static function preview(): ?string
    {
        // Same file, accessed as a view name for Filament preview rendering
        return 'components.filament-static-pages.blocks.' . static::type();
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
