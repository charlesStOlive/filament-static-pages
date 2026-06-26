<?php

namespace CharlesStOlive\FilamentStaticPages\Filament\Resources\Pages\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->defaultSort('order', 'asc')
            ->columns([
                TextColumn::make('titre')
                    ->label('Titre')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                ToggleColumn::make('is_homepage')
                    ->label('Page d’accueil')
                    ->beforeStateUpdated(function ($record, $state) {
                        if (! $state) {
                            return;
                        }

                        $model = config('filament-static-pages.model');

                        $model::query()
                            ->whereKeyNot($record->getKey())
                            ->update([
                                'is_homepage' => false,
                            ]);
                    }),

                ToggleColumn::make('is_in_header')
                    ->label('Dans le header'),

                ToggleColumn::make('is_in_footer')
                    ->label('Dans le footer'),

                TextColumn::make('order')
                    ->label('Ordre')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'published' => 'success',
                        'draft' => 'warning',
                        'archived' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('published_at')
                    ->label('Publié le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Modifié le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('')
                    ->icon('heroicon-o-pencil')
                    ->tooltip('Modifier'),

                Action::make('preview')
                    ->label('')
                    ->icon('heroicon-o-eye')
                    ->tooltip('Voir la page')
                    ->url(
                        fn($record) => $record->slug
                            ? route(config('filament-static-pages.route.name', 'page'), [
                                'slug' => $record->slug,
                            ])
                            : null
                    )
                    ->openUrlInNewTab()
                    ->color('gray')
                    ->visible(fn($record) => $record->status === 'published'),

                DeleteAction::make()
                    ->label('')
                    ->icon('heroicon-o-trash')
                    ->tooltip('Supprimer'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
