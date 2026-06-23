<?php

namespace Notilac\FilamentStaticPages;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Notilac\FilamentStaticPages\Filament\Resources\Pages\PageResource;

class FilamentStaticPagesPlugin implements Plugin
{
    protected bool $hasPageResource = true;

    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'notilac-filament-static-pages';
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

    public function register(Panel $panel): void
    {
        if (! $this->hasPageResource()) {
            return;
        }

        if (! config('filament-static-pages.filament.register_resource', true)) {
            return;
        }

        $panel->resources([
            PageResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
