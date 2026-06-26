<?php

namespace CharlesStOlive\FilamentStaticPages\Blocks\Concerns;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Support\Str;

trait HasPageBlockFields
{
    public static function baseFields(): array
    {
        return [
            Hidden::make('block_id')
                ->default(fn() => (string) Str::uuid()),

            TextInput::make('anchor')
                ->label('Ancre (ID)')
                ->helperText('Identifiant unique pour créer un lien vers cette section')
                ->placeholder('ex: ma-section'),
        ];
    }

    public static function titleEditor(string $key = 'title', string $label = 'Titre'): RichEditor
    {
        $fieldName = str_starts_with($key, 'html_') ? $key : 'html_' . $key;

        return RichEditor::make($fieldName)
            ->label($label)
            ->required()
            ->toolbarButtons([
                'bold',
                'clearFormatting',
            ]);
    }

    public static function fullEditor(string $key, string $label): RichEditor
    {
        $fieldName = str_starts_with($key, 'html_') ? $key : 'html_' . $key;

        $plugins = collect(config('filament-static-pages.rich_editor.plugins', []))
            ->map(fn($class) => $class::make())
            ->all();

        return RichEditor::make($fieldName)
            ->label($label)
            ->plugins($plugins)
            ->toolbarButtons([
                ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript'],
                ['h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                ['blockquote', 'codeBlock', 'bulletList'],
                ['undo', 'redo', 'clearFormatting'],
            ]);
    }

    public static function actionsEditor(): Repeater
    {
        return Repeater::make('boutons')
            ->label('Boutons')
            ->schema([
                TextInput::make('texte')
                    ->label('Texte du bouton')
                    ->required(),

                Select::make('couleur')
                    ->label('Couleur')
                    ->options([
                        'primary' => 'Primaire',
                        'secondary' => 'Secondaire',
                        'tertiary' => 'Tertiaire',
                    ])
                    ->default('primary')
                    ->required(),

                Select::make('type_lien')
                    ->label('Type de lien')
                    ->options([
                        'page' => 'Page du site',
                        'externe' => 'URL externe',
                    ])
                    ->default('page')
                    ->live()
                    ->required(),

                TextInput::make('url_externe')
                    ->label('URL externe')
                    ->placeholder('https://exemple.com')
                    ->visible(fn($get) => $get('type_lien') === 'externe'),
            ])
            ->collapsible()
            ->maxItems(4)
            ->columns(2)
            ->collapsed()
            ->addActionLabel('Ajouter un bouton')
            ->itemLabel(fn(array $state): ?string => $state['texte'] ?? null);
    }

    public static function photoField(): Grid
    {
        return Grid::make(1)
            ->statePath('photo_config')
            ->schema([
                FileUpload::make('image_url')
                    ->label('Photo')
                    ->disk('public')
                    ->directory('pages/photos')
                    ->image()
                    ->imageEditor()
                    ->maxSize(5000),

                Select::make('display_type')
                    ->label('Type d’affichage')
                    ->options([
                        'mask_brush_square' => 'Trait de pinceaux carré',
                        'mask_brush_169' => 'Trait de pinceaux 16:9',
                        'full_cover' => 'Couvrant',
                    ])
                    ->default('mask_brush_square')
                    ->live(),

                Select::make('position')
                    ->label('Position de la photo')
                    ->options([
                        'center' => 'Centre',
                        'top' => 'Haut',
                        'bottom' => 'Bas',
                        'left' => 'Gauche',
                        'right' => 'Droite',
                    ])
                    ->default('center')
                    ->visible(fn($get) => $get('display_type') === 'full_cover'),
            ]);
    }

    public static function styleTab(bool $hero = false): Tab
    {
        return Tab::make('Style')
            ->schema([
                Grid::make([
                    'default' => 1,
                    'md' => 2,
                ])->schema([
                    Grid::make(1)
                        ->statePath('ambiance')
                        ->schema([
                            Select::make('couleur_primaire')
                                ->label('Couleur du titre')
                                ->options($hero ? [
                                    'primary' => 'Primaire',
                                    'secondary' => 'Secondaire',
                                    'primary-brush' => 'Pinceau primaire',
                                    'secondary-brush' => 'Pinceau secondaire',
                                ] : [
                                    'primary' => 'Primaire',
                                    'secondary' => 'Secondaire',
                                ])
                                ->default('secondary'),

                            Toggle::make('animate')
                                ->label('Activer les animations')
                                ->default(true),

                            Toggle::make('is_hidden')
                                ->label('Cacher temporairement ce bloc')
                                ->default(false),

                            Toggle::make('minH70vh')
                                ->label('Hauteur minimale 70vh')
                                ->default($hero),
                        ]),
                ]),
            ]);
    }
}
