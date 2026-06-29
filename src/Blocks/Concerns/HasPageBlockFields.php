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

    /**
     * Champ "fond de section" — grid complet avec statePath 'background_datas'.
     *
     * 3 modes disponibles :
     *  - aucun      : pas de fond spécifique (fond blanc par défaut)
     *  - image      : image de fond uploadée + couche de blanc + dégradé de couleurs
     *  - filtre     : masque SVG coloré appliqué sur l'image (effets artistiques)
     *
     * Les champs conditionnels sont révélés/masqués via ->visible(fn($get)...).
     */
    public static function backgroundField(): Grid
    {
        return Grid::make(1)
            ->statePath('background_datas')
            ->schema([
                // ── Mode de fond ──────────────────────────────────────────────
                Select::make('mode')
                    ->label('Mode de fond')
                    ->options([
                        'aucun'  => 'Aucun (fond blanc)',
                        'image'  => 'Image de fond et/ou dégradés',
                        'filtre' => 'Filtre artistique (masque SVG)',
                    ])
                    ->default('aucun')
                    ->live()
                    ->required(),

                // ── Image de fond — visible uniquement en mode "image" ─────
                FileUpload::make('image_background')
                    ->label('Image de fond')
                    ->disk('public')
                    ->directory('images-bg')
                    ->image()
                    ->imageEditor()
                    ->maxSize(5000)
                    ->helperText('Recommandé : 1920×1080px, format WebP')
                    ->visible(fn ($get) => $get('mode') === 'image'),

                // ── Couche de blanc — atténue l'image pour lisibilité du texte
                Select::make('couche_blanc')
                    ->label('Voile blanc (lisibilité)')
                    ->helperText('Ajoute un voile semi-transparent pour améliorer la lisibilité du texte')
                    ->options([
                        'aucun'                                        => 'Aucun',
                        'bg-gradient-to-b from-white/30 to-white/70'  => 'Normal',
                        'bg-gradient-to-b from-white/50 to-white/100' => 'Fort',
                        'bg-gradient-to-l from-white/80 to-white/70'  => 'Dense',
                    ])
                    ->default('aucun')
                    ->visible(fn ($get) => $get('mode') === 'image'),

                // ── Dégradés de couleurs — superposés à l'image ───────────
                Select::make('gradients')
                    ->label('Dégradé de couleurs')
                    ->helperText('Dégradé de la palette de couleurs du site superposé au fond')
                    ->options([
                        'aucun'                                                              => 'Aucun',
                        'bg-gradient-to-r from-primary-500 to-secondary-500'                => 'Primaire → Secondaire (horizontal)',
                        'bg-gradient-to-b from-primary-500 to-secondary-500'                => 'Primaire → Secondaire (vertical)',
                        'bg-gradient-to-br from-primary-500 via-secondary-500 to-tertiary-500' => 'Primaire → Secondaire → Tertiaire (diagonal)',
                        'bg-gradient-to-t from-secondary-500 to-primary-500'                => 'Secondaire → Primaire (montant)',
                        'bg-gradient-to-bl from-tertiary-500 via-primary-500 to-secondary-500' => 'Tertiaire → Primaire → Secondaire (diagonal inverse)',
                    ])
                    ->default('aucun')
                    ->visible(fn ($get) => $get('mode') === 'image'),

                // ── Masque SVG — visible uniquement en mode "filtre" ──────
                Select::make('mask')
                    ->label('Masque')
                    ->helperText('Forme de masque appliquée sur l\'image (effet aquarelle/artistique)')
                    ->default('hero-mask-1')
                    ->options([
                        'hero-mask-1' => 'Masque 1',
                        'hero-mask-2' => 'Masque 2',
                        'hero-mask-3' => 'Masque 3',
                        'hero-mask-4' => 'Masque 4',
                    ])
                    ->visible(fn ($get) => $get('mode') === 'filtre')
                    ->required(fn ($get) => $get('mode') === 'filtre'),

                // ── Couleur du masque ─────────────────────────────────────
                Select::make('mask_color')
                    ->label('Couleur du masque')
                    ->options([
                        'bg-primary-500'   => 'Primaire',
                        'bg-primary-200'   => 'Primaire claire',
                        'bg-secondary-500' => 'Secondaire',
                        'bg-secondary-200' => 'Secondaire claire',
                        'bg-tertiary-500'  => 'Tertiaire',
                        'bg-tertiary-200'  => 'Tertiaire claire',
                    ])
                    ->visible(fn ($get) => $get('mode') === 'filtre'),
            ])
            ->columnSpan(1);
    }

    /**
     * Onglet "Style" — présent dans tous les blocs.
     *
     * @param bool $hero  true = options spécifiques Hero (pinceau, minH70vh activé par défaut, couleurs brush)
     */
    public static function styleTab(bool $hero = false): Tab
    {
        return Tab::make('Style')
            ->schema([
                Grid::make([
                    'default' => 1,
                    'md' => 2,
                ])->schema([
                    // ── Colonne gauche : ambiance ─────────────────────────────
                    Grid::make(1)
                        ->statePath('ambiance')
                        ->schema([
                            // Couleur du titre (le Hero a en plus les options "pinceau")
                            Select::make('couleur_primaire')
                                ->label('Couleur du titre')
                                ->options($hero ? [
                                    'primary'          => 'Primaire',
                                    'secondary'        => 'Secondaire',
                                    'primary-brush'    => 'Pinceau primaire (effet pinceau)',
                                    'secondary-brush'  => 'Pinceau secondaire',
                                ] : [
                                    'primary'   => 'Primaire',
                                    'secondary' => 'Secondaire',
                                ])
                                ->default('secondary'),

                            // Style des listes (uniquement pour les blocs de contenu)
                            ...(!$hero ? [
                                Select::make('style_listes')
                                    ->label('Style des listes')
                                    ->helperText('Couleur utilisée pour les puces et numéros de liste')
                                    ->options([
                                        'alternance' => 'Alternance primaire / secondaire',
                                        'primary'    => 'Primaire uniquement',
                                        'secondary'  => 'Secondaire uniquement',
                                    ])
                                    ->default('alternance'),
                            ] : []),

                            Toggle::make('animate')
                                ->label('Activer les animations d\'apparition')
                                ->helperText('Les éléments apparaissent progressivement lors du scroll')
                                ->default(true),

                            Toggle::make('is_hidden')
                                ->label('Masquer ce bloc temporairement')
                                ->helperText('Le bloc reste en base de données mais n\'est pas affiché')
                                ->default(false),

                            Toggle::make('minH70vh')
                                ->label('Hauteur minimale 70vh')
                                ->helperText('Force la section à occuper au moins 70% de la hauteur d\'écran')
                                ->default($hero),

                            // Séparateur (uniquement pour les blocs de contenu)
                            ...(!$hero ? [
                                Toggle::make('afficher_separateur')
                                    ->label('Afficher un séparateur en bas')
                                    ->default(false),
                            ] : []),
                        ])
                        ->columnSpan(1),

                    // ── Colonne droite : fond de section ──────────────────────
                    static::backgroundField(),
                ]),
            ]);
    }
}
