<?php

namespace CharlesStOlive\FilamentStaticPages\Filament\Widgets;

use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Gate;

class ConstructionModeWidget extends Widget
{
    protected string $view = 'filament-static-pages::filament.widgets.construction-mode-widget';

    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = null;

    public bool $isActive = false;

    /**
     * Réservé à qui en a le droit quand l'application gère les permissions : elle définit alors une
     * ability Gate au nom de cette classe (filament-permission-manager : `widgets.{widget}.viewany`,
     * créée par permissions:sync). Sans cette ability, le widget reste visible de tout le panel.
     */
    public static bool $requiresPermission = true;

    public static function canView(): bool
    {
        return ! Gate::has(static::class) || Gate::allows(static::class);
    }

    public function mount(): void
    {
        $settings = app(config('filament-static-pages.settings.class'));
        $this->isActive = $settings->construction['activate'] ?? false;
    }

    public function toggleConstruction(): void
    {
        $settings = app(config('filament-static-pages.settings.class'));
        $construction = $settings->construction;
        $construction['activate'] = ! $construction['activate'];
        $settings->construction = $construction;
        $settings->save();

        $this->isActive = $construction['activate'];

        Notification::make()
            ->title('Mode construction ' . ($construction['activate'] ? 'activé' : 'désactivé'))
            ->success()
            ->send();
    }
}
