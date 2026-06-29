<?php

namespace CharlesStOlive\FilamentStaticPages;

use Illuminate\Support\Facades\View;
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
        // Namespace 'filament-static-pages' → resources/views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'filament-static-pages');

        // Register sub-blocks from config
        foreach (config('filament-static-pages.sub_blocks', []) as $class) {
            SubBlockRegistry::register($class);
        }

        // Injecte $headerPages, $footerPages et $siteLogo dans les vues front configurées
        $this->shareNavigationData();

        // Publish CSS stub
        $this->publishes([
            __DIR__ . '/../stubs/css/filament-static-pages.css' => resource_path('css/filament-static-pages.css'),
        ], 'filament-static-pages-css');
    }

    private function shareNavigationData(): void
    {
        if (! config('filament-static-pages.navigation.enabled', true)) {
            return;
        }

        $views = config('filament-static-pages.navigation.views', []);

        if (empty($views)) {
            return;
        }

        View::composer($views, function ($view) {
            $pageModel = config('filament-static-pages.model');

            if (! $pageModel || ! class_exists($pageModel)) {
                $view->with([
                    'headerPages' => collect(),
                    'footerPages' => collect(),
                    'siteLogo' => null,
                ]);

                return;
            }

            static $headerPages = null;
            static $footerPages = null;
            static $siteLogo = null;
            static $siteLogoResolved = false;

            $headerPages ??= $pageModel::query()
                ->where('is_in_header', true)
                ->where('status', 'published')
                ->orderBy('order', 'asc')
                ->get(['titre', 'slug']);

            $footerPages ??= $pageModel::query()
                ->where('is_in_footer', true)
                ->where('status', 'published')
                ->orderBy('order', 'asc')
                ->get(['titre', 'slug']);

            if (! $siteLogoResolved) {
                $siteLogo = $this->resolveSiteLogo();
                $siteLogoResolved = true;
            }

            $view->with([
                'headerPages' => $headerPages,
                'footerPages' => $footerPages,
                'siteLogo' => $siteLogo,
            ]);
        });
    }

    private function resolveSiteLogo(): mixed
    {
        $settingsClass = config('filament-static-pages.settings.class');

        if (! $settingsClass || ! class_exists($settingsClass)) {
            return null;
        }

        $settings = app($settingsClass);

        return data_get(
            $settings,
            config('filament-static-pages.settings.logo_key', 'logo')
        );
    }
}
