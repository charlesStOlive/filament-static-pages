<?php

use Illuminate\Support\Facades\Route;
use CharlesStOlive\FilamentStaticPages\Livewire\StaticPage;
use CharlesStOlive\FilamentStaticPages\Http\Controllers\PreviewBlockController;


if (config('filament-static-pages.route.enabled', true)) {
    Route::middleware(config('filament-static-pages.route.middleware', ['web']))
        ->get(
            trim(config('filament-static-pages.route.prefix', 'pages'), '/') . '/{slug}',
            StaticPage::class
        )
        ->where('slug', '[a-zA-Z0-9\-_]+')
        ->name(config('filament-static-pages.route.name', 'page'));
}




Route::middleware([
    'web',
    'auth',
    'signed',
])
    ->get('/filament-static-pages/preview/block/{key}', PreviewBlockController::class)
    ->name('filament-static-pages.preview.block');
