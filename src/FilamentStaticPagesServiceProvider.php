<?php

namespace Notilac\FilamentStaticPages;

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
        Blade::anonymousComponentPath(
            __DIR__ . '/../resources/views/components',
            'static-pages'
        );
    }
}
