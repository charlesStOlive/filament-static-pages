<?php

namespace CharlesStOlive\FilamentStaticPages\Livewire;

use Livewire\Component;

class ConstructionPage extends Component
{
    public function mount()
    {
        // Si le mode construction n'est pas activé, rediriger vers l'accueil
        $settingsClass = config('filament-static-pages.settings.class');
        $settings = app($settingsClass);
        if (!($settings->construction['activate'] ?? false)) {
            return redirect()->route('home');
        }
    }

    public function render()
    {
        $settingsClass = config('filament-static-pages.settings.class');
        $settings = app($settingsClass);

        return view('filament-static-pages::livewire.construction-page')
            ->layout('filament-static-pages::layouts.construction')
            ->title($settings->construction['titre'] ?? 'Site en maintenance');
    }
}
