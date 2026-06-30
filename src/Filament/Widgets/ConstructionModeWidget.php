<?php

namespace CharlesStOlive\FilamentStaticPages\Filament\Widgets;

use Filament\Notifications\Notification;
use Filament\Widgets\Widget;

class ConstructionModeWidget extends Widget
{
    protected string $view = 'filament-static-pages::filament.widgets.construction-mode-widget';

    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = null;

    public bool $isActive = false;

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
