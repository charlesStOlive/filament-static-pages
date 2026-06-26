<?php

namespace CharlesStOlive\FilamentStaticPages\Blocks;

use Illuminate\Support\Collection;

class PageBlockRegistry
{
    public function blockClasses(): array
    {
        return config('filament-static-pages.blocks', []);
    }

    public function blocks(): Collection
    {
        return collect($this->blockClasses())
            ->filter(fn(string $class) => is_subclass_of($class, PageBlock::class));
    }

    public function filamentBlocks(): array
    {
        return $this->blocks()
            ->map(fn(string $class) => $class::filamentBlock())
            ->values()
            ->all();
    }

    public function find(string $type): ?string
    {
        return $this->blocks()
            ->first(fn(string $class) => $class::type() === $type);
    }

    public function componentFor(string $type): ?string
    {
        $class = $this->find($type);

        return $class ? $class::component() : null;
    }
}
