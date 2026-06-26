<?php

namespace App\Filament\StaticPages\SubBlocks;

use CharlesStOlive\FilamentStaticPages\Blocks\Concerns\HasPageBlockFields;
use CharlesStOlive\FilamentStaticPages\Blocks\SubBlocks\Contracts\SubBlockContract;
use Filament\Schemas\Components\Grid;

class PhotoTexteSubBlock implements SubBlockContract
{
    use HasPageBlockFields;

    public static function type(): string
    {
        return 'photo-texte';
    }

    public static function label(): string
    {
        return 'Photo + Texte';
    }

    public static function schema(): array
    {
        return [
            Grid::make(['default' => 1, 'md' => 2])->schema([
                static::photoField(),
                static::fullEditor('texts', 'Texte principal'),
            ]),
        ];
    }

    public static function view(): string
    {
        return 'components.filament-static-pages.blocks.sub.photo-texte';
    }
}
