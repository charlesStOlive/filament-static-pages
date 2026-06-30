<?php

namespace CharlesStOlive\FilamentStaticPages\Livewire;

use App\Models\Page;
use Livewire\Component;
use CharlesStOlive\FilamentStaticPages\Livewire\Concerns\HandlesConstructionMode;

class HomePage extends Component
{
    use HandlesConstructionMode;

    public mixed $page;

    public string $slug;

    public function mount($slug = 'home')
    {
        $this->checkConstruction();
        
        $this->slug = $slug;

        $model = config('filament-static-pages.model');
        
        // Récupérer la page par slug ou afficher 404
        $this->page = $model::where('is_homepage', true)->first();

        if (!$this->page) {
            abort(404, "Page '{$slug}' non trouvée");
        }
    }

    
    public function render()
    {
        return view('livewire.front.static-page')
            ->layout('layouts.front', [
                'hasForm' => $this->page->has_form,
                'metaDescription' => $this->page->meta_description,
                'metaKeywords' => $this->page->meta_keywords,
            ])
            ->title($this->page->titre);
    }
}
