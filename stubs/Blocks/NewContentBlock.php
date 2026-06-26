<?php

namespace App\Filament\StaticPages\Blocks;

use CharlesStOlive\FilamentStaticPages\Blocks\Concerns\HasPageBlockFields;
use CharlesStOlive\FilamentStaticPages\Blocks\PageBlock;
use CharlesStOlive\FilamentStaticPages\Blocks\SubBlockRegistry;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class NewContentBlock extends PageBlock
{
    use HasPageBlockFields;

    public static function type(): string
    {
        return 'new-content';
    }

    public static function label(): string
    {
        return 'Contenu multiple';
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
                            Builder::make('subcontents')
                                ->label('Contenu de la section')
                                ->collapsible()
                                ->cloneable()
                                ->blocks(SubBlockRegistry::getFilamentBlocks()),
                        ]),

                    static::styleTab(),
                ]),
        ];
    }
}
