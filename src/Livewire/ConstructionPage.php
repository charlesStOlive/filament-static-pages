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

        return view('livewire.front.construction-page')
            ->layout('layouts.construction')
            ->title($settings->construction['titre'] ?? 'Site en maintenance');
    }
}
