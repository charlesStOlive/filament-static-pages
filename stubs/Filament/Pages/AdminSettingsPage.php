<?php

namespace App\Filament\Pages;

use App\Settings\AdminSettings;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class AdminSettingsPage extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string $settings = AdminSettings::class;

    protected static ?string $navigationLabel = 'Paramètres CMS';

    public static function getNavigationGroup(): ?string
    {
        return config('filament-static-pages.filament.navigation_group', 'CMS');
    }

    protected static ?string $title = 'Paramètres du site';

    protected static ?int $navigationSort = 99;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations de contact')
                    ->description('Configurez les informations de contact de votre organisation')
                    ->schema([
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->placeholder('admin@example.com'),

                        TextInput::make('telephone')
                            ->label('Téléphone')
                            ->tel()
                            ->required()
                            ->placeholder('+33 1 23 45 67 89'),

                        Textarea::make('adresse')
                            ->label('Adresse')
                            ->required()
                            ->rows(3),

                        Textarea::make('horaire')
                            ->label('Horaires')
                            ->required()
                            ->rows(3),

                        TextInput::make('mailRecepteur')
                            ->label('Email récepteur (formulaire de contact)')
                            ->email()
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Apparence')
                    ->description('Personnalisez l\'apparence de votre site')
                    ->schema([
                        FileUpload::make('logo')
                            ->label('Logo')
                            ->image()
                            ->disk('public')
                            ->directory('logos')
                            ->acceptedFileTypes(['image/png', 'image/jpg', 'image/jpeg', 'image/svg+xml'])
                            ->maxSize(2048),

                        Textarea::make('footerText')
                            ->label('Texte du footer')
                            ->required()
                            ->rows(2),
                    ]),

                Section::make('Mode maintenance')
                    ->description('Lorsqu\'activé, seuls les administrateurs connectés voient le site')
                    ->schema([
                        Toggle::make('construction.activate')
                            ->label('Activer le mode maintenance')
                            ->live(),

                        Group::make()
                            ->schema([
                                TextInput::make('construction.titre')
                                    ->label('Titre de la page de maintenance')
                                    ->required()
                                    ->placeholder('Site en maintenance'),

                                Textarea::make('construction.description')
                                    ->label('Description')
                                    ->required()
                                    ->rows(3),
                            ])
                            ->visible(fn(callable $get) => $get('construction.activate'))
                            ->columns(1),
                    ]),
            ]);
    }
}
