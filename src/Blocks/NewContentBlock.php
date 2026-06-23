<?php

namespace Notilac\FilamentStaticPages\Blocks;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Notilac\FilamentStaticPages\Blocks\Concerns\HasPageBlockFields;

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

                            Textarea::make('description')
                                ->label('Description'),

                            Builder::make('subcontents')
                                ->label('Contenu de la section')
                                ->collapsible()
                                ->cloneable()
                                ->blocks([
                                    Block::make('texte-photo')
                                        ->label('Texte + Photo')
                                        ->schema([
                                            Grid::make([
                                                'default' => 1,
                                                'md' => 2,
                                            ])->schema([
                                                static::fullEditor('texts', 'Texte principal'),
                                                static::photoField(),
                                            ]),
                                        ]),

                                    Block::make('photo-texte')
                                        ->label('Photo + Texte')
                                        ->schema([
                                            Grid::make([
                                                'default' => 1,
                                                'md' => 2,
                                            ])->schema([
                                                static::photoField(),
                                                static::fullEditor('texts', 'Texte principal'),
                                            ]),
                                        ]),

                                    Block::make('texte-texte')
                                        ->label('Texte + Texte')
                                        ->schema([
                                            Grid::make([
                                                'default' => 1,
                                                'md' => 2,
                                            ])->schema([
                                                static::fullEditor('texts', 'Texte principal'),
                                                static::fullEditor('secondary_text', 'Texte secondaire'),
                                            ]),
                                        ]),
                                ]),
                        ]),

                    static::styleTab(),
                ]),
        ];
    }
}
