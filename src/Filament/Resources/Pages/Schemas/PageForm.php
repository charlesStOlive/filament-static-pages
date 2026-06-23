<?php

namespace Notilac\FilamentStaticPages\Filament\Resources\Pages\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Illuminate\Support\Str;
use Notilac\FilamentStaticPages\Blocks\PageBlockRegistry;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(4)->schema([
                    Section::make('Contenu')
                        ->schema([
                            Builder::make('contents')
                                ->label('Blocs de contenu')
                                ->blockPreviews()
                                ->collapsible()
                                ->cloneable()
                                ->grow()
                                ->blockNumbers(false)
                                ->editAction(
                                    fn($action) => $action->modalWidth(Width::SevenExtraLarge)
                                )
                                ->extraItemActions([
                                    Action::make('toggleVisibility')
                                        ->icon('heroicon-o-eye-slash')
                                        ->label('Basculer la visibilité')
                                        ->action(function (array $arguments, Builder $component): void {
                                            $state = $component->getState();
                                            $itemId = $arguments['item'];

                                            $currentValue = $state[$itemId]['data']['is_hidden'] ?? false;
                                            $state[$itemId]['data']['is_hidden'] = ! $currentValue;

                                            $component->state($state);
                                        }),
                                ])
                                ->blocks(app(PageBlockRegistry::class)->filamentBlocks()),
                        ])
                        ->columnSpan(3),

                    Section::make('Détails de la page')
                        ->schema([
                            TextInput::make('titre')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (string $operation, $state, $set) {
                                    if ($operation !== 'create') {
                                        return;
                                    }

                                    $set('slug', Str::slug($state));
                                }),

                            TextInput::make('slug')
                                ->required()
                                ->suffixAction(self::getPreviewAction()),

                            Select::make('status')
                                ->options([
                                    'draft' => 'Brouillon',
                                    'published' => 'Publié',
                                    'archived' => 'Archivé',
                                ])
                                ->default('draft'),

                            Toggle::make('is_homepage')
                                ->label('Page d’accueil')
                                ->helperText('Une seule page peut être définie comme page d’accueil'),

                            Toggle::make('is_in_header')
                                ->label('Afficher dans le header'),

                            Toggle::make('is_in_footer')
                                ->label('Afficher dans le footer'),

                            Toggle::make('has_form')
                                ->label('Afficher le formulaire de contact'),

                            Textarea::make('meta_description')
                                ->label('Description SEO')
                                ->rows(3)
                                ->maxLength(160),

                            TextInput::make('meta_keywords')
                                ->label('Mots-clés SEO'),

                            DateTimePicker::make('published_at')
                                ->label('Date de publication'),
                        ])
                        ->footerActions([
                            self::getPreviewAction()
                                ->label('Prévisualiser la page')
                                ->button()
                                ->color('info')
                                ->size('sm'),
                        ])
                        ->columnSpan(1),
                ]),
            ]);
    }

    public static function getPreviewAction(): Action
    {
        return Action::make('preview')
            ->icon('heroicon-o-eye')
            ->tooltip('Prévisualiser la page')
            ->url(
                fn($get) => $get('slug')
                    ? route(config('filament-static-pages.route.name', 'page'), [
                        'slug' => $get('slug'),
                    ])
                    : null
            )
            ->openUrlInNewTab()
            ->color('gray')
            ->disabled(fn($get) => $get('status') !== 'published');
    }
}
