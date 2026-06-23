<?php

namespace Notilac\FilamentStaticPages\Blocks;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Notilac\FilamentStaticPages\Blocks\Concerns\HasPageBlockFields;

class ContentBlock extends PageBlock
{
    use HasPageBlockFields;

    public static function type(): string
    {
        return 'content';
    }

    public static function label(): string
    {
        return 'Contenu';
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

                            Grid::make([
                                'default' => 1,
                                'md' => 2,
                            ])->schema([
                                static::fullEditor('texts', 'Texte principal'),
                                static::photoField(),
                            ]),

                            Toggle::make('left_image')
                                ->label('Image à gauche')
                                ->helperText('Par défaut, l’image est à droite')
                                ->default(false),
                        ]),

                    static::styleTab(),
                ]),
        ];
    }
}
