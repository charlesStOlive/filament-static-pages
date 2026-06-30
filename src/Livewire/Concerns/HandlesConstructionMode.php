<?php

namespace CharlesStOlive\FilamentStaticPages\Livewire\Concerns;

trait HandlesConstructionMode
{
    /**
     * Vérifie si le mode construction est actif et redirige si nécessaire
     */
    protected function checkConstruction(): void
    {

        $settingsClass = config('filament-static-pages.settings.class');

        $settings = app($settingsClass);

        \Log::info('Checking construction mode: ' . json_encode($settings->construction));

        if (($settings->construction['activate'] ?? false) && !auth()->check()) {
            redirect()->route('construction');
        }
    }
}
