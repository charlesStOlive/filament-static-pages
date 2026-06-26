<?php

namespace App\Filament\StaticPages\SubBlocks;

use CharlesStOlive\FilamentStaticPages\Blocks\Concerns\HasPageBlockFields;
use CharlesStOlive\FilamentStaticPages\Blocks\SubBlocks\Contracts\SubBlockContract;
use Filament\Schemas\Components\Grid;

class TextePhotoSubBlock implements SubBlockContract
{
    use HasPageBlockFields;

    public static function type(): string
    {
        return 'texte-photo';
    }

    public static function label(): string
    {
        return 'Texte + Photo';
    }

    public static function schema(): array
    {
        return [
            Grid::make(['default' => 1, 'md' => 2])->schema([
                static::fullEditor('texts', 'Texte principal'),
                static::photoField(),
            ]),
        ];
    }

    public static function view(): string
    {
        return 'components.filament-static-pages.blocks.sub.texte-photo';
    }
}
