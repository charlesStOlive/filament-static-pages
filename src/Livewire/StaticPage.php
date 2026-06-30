<?php

namespace CharlesStOlive\FilamentStaticPages\Livewire;

use Livewire\Component;
use CharlesStOlive\FilamentStaticPages\Livewire\Concerns\HandlesConstructionMode;

class StaticPage extends Component
{
    use HandlesConstructionMode;

    public mixed $page;

    public string $slug;

    public function mount(string $slug): void
    {
        $this->checkConstruction();

        $this->slug = $slug;

        $model = config('filament-static-pages.model');

        $this->page = $model::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (! $this->page) {
            abort(404, "Page '{$slug}' non trouvée");
        }
    }

    public function render()
    {
        return view(config('filament-static-pages.front.view', 'filament-static-pages::livewire.static-page'))
            ->layout(config('filament-static-pages.front.layout', 'layouts.front'), [
                'hasForm' => $this->page->has_form,
                'metaDescription' => $this->page->meta_description,
                'metaKeywords' => $this->page->meta_keywords,
            ])
            ->title($this->page->titre);
    }
}
