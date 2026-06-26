<?php

namespace CharlesStOlive\FilamentStaticPages;

use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use CharlesStOlive\FilamentStaticPages\Blocks\SubBlockRegistry;
use CharlesStOlive\FilamentStaticPages\Commands\InstallCommand;

class FilamentStaticPagesServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-static-pages')
            ->hasConfigFile()
            ->hasViews()
            ->hasRoute('web')
            ->hasMigration('create_cms_pages_table')
            ->hasCommand(InstallCommand::class);
    }

    public function packageBooted(): void
    {
        // Namespace 'filament-static-pages' → resources/views (livewire template, etc.)
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'filament-static-pages');

        // Register sub-blocks from config (populated after install)
        foreach (config('filament-static-pages.sub_blocks', []) as $class) {
            SubBlockRegistry::register($class);
        }

        // Publish CSS stub
        $this->publishes([
            __DIR__ . '/../stubs/css/filament-static-pages.css' => resource_path('css/filament-static-pages.css'),
        ], 'filament-static-pages-css');
    }
}
