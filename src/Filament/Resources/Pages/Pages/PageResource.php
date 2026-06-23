<?php

namespace Notilac\FilamentStaticPages\Filament\Resources\Pages;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Notilac\FilamentStaticPages\Filament\Resources\Pages\Pages\CreatePage;
use Notilac\FilamentStaticPages\Filament\Resources\Pages\Pages\EditPage;
use Notilac\FilamentStaticPages\Filament\Resources\Pages\Pages\ListPages;
use Notilac\FilamentStaticPages\Filament\Resources\Pages\Schemas\PageForm;
use Notilac\FilamentStaticPages\Filament\Resources\Pages\Tables\PagesTable;

class PageResource extends Resource
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'titre';

    public static function getModel(): string
    {
        return config('filament-static-pages.model');
    }

    public static function getNavigationGroup(): ?string
    {
        return config('filament-static-pages.filament.navigation_group', 'CMS');
    }

    public static function getNavigationLabel(): string
    {
        return config('filament-static-pages.filament.navigation_label', 'Pages');
    }

    public static function form(Schema $schema): Schema
    {
        return PageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PagesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPages::route('/'),
            'create' => CreatePage::route('/create'),
            'edit' => EditPage::route('/{record}/edit'),
        ];
    }
}
