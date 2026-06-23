<?php

use Illuminate\Support\Facades\Route;
use Notilac\FilamentStaticPages\Livewire\StaticPage;

if (config('filament-static-pages.route.enabled', true)) {
    Route::middleware(config('filament-static-pages.route.middleware', ['web']))
        ->get(
            trim(config('filament-static-pages.route.prefix', 'pages'), '/') . '/{slug}',
            StaticPage::class
        )
        ->where('slug', '[a-zA-Z0-9\-_]+')
        ->name(config('filament-static-pages.route.name', 'page'));
}
