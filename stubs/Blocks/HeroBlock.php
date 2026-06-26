<?php

namespace App\Filament\StaticPages\Blocks;

use CharlesStOlive\FilamentStaticPages\Blocks\Concerns\HasPageBlockFields;
use CharlesStOlive\FilamentStaticPages\Blocks\PageBlock;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class HeroBlock extends PageBlock
{
    use HasPageBlockFields;

    public static function type(): string
    {
        return 'hero';
    }

    public static function label(): string
    {
        return 'Bannière Hero';
    }

    public static function schema(): array
    {
        return [
            Tabs::make('Tabs')
                ->tabs([
                    Tab::make('Contenu')
                        ->schema([
                            ...static::baseFields(),
                            static::titleEditor('title', 'Titre'),
                            Textarea::make('description')->label('Description'),
                            static::actionsEditor(),
                        ]),

                    static::styleTab(hero: true),
                ]),
        ];
    }
}
