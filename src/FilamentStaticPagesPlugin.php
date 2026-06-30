<?php

namespace CharlesStOlive\FilamentStaticPages;

use Filament\Contracts\Plugin;
use Filament\Panel;
use CharlesStOlive\FilamentStaticPages\Filament\Resources\Pages\PageResource;
use CharlesStOlive\FilamentStaticPages\Filament\Widgets\ConstructionModeWidget;

class FilamentStaticPagesPlugin implements Plugin
{
    protected bool $hasPageResource = true;

    protected bool $hasConstructionWidget = false;

    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'charlesstolive-filament-static-pages';
    }

    public function pageResource(bool $condition = true): static
    {
        $this->hasPageResource = $condition;

        return $this;
    }

    public function hasPageResource(): bool
    {
        return $this->hasPageResource;
    }

    public function constructionWidget(bool $condition = true): static
    {
        $this->hasConstructionWidget = $condition;

        return $this;
    }

    public function hasConstructionWidget(): bool
    {
        return $this->hasConstructionWidget;
    }

    public function register(Panel $panel): void
    {
        if ($this->hasPageResource() && config('filament-static-pages.filament.register_resource', true)) {
            $panel->resources([
                PageResource::class,
            ]);
        }

        if ($this->hasConstructionWidget()) {
            $panel->widgets([
                ConstructionModeWidget::class,
            ]);
        }
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
