<?php

namespace CharlesStOlive\FilamentStaticPages;

use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentStaticPagesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-static-pages')
            ->hasConfigFile()
            ->hasViews()
            ->hasRoute('web')
            ->hasMigration('create_cms_pages_table');
    }

    public function packageBooted(): void
    {
        // Register view namespace so view('static-pages::...') resolves correctly
        // (needed as fallback when DynamicComponent renders <x-static-pages::...> tags)
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'static-pages');

        // Register anonymous component path for <x-static-pages::blocks.hero> tags
        Blade::anonymousComponentPath(
            __DIR__ . '/../resources/views/components',
            'static-pages'
        );
    }
}
