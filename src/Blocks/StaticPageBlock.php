<?php

namespace CharlesStOlive\FilamentStaticPages\Blocks;

use Filament\Forms\Components\Builder\Block;
use Illuminate\Contracts\View\View;

/**
 * Custom Block that overrides renderPreview() to wrap flat field data
 * into the structure expected by block views: ['type' => ..., 'data' => ...].
 *
 * Filament's default renderPreview() passes block fields as flat variables,
 * but our block views expect $block = ['type' => '...', 'data' => [...]].
 */
class StaticPageBlock extends Block
{
    public function renderPreview(array $data): View
    {
        return view(
            $this->evaluate($this->preview),
            [
                'block' => [
                    'type' => $this->getName(),
                    'data' => $data,
                ],
                'mode'  => 'preview',
                'page'  => null,
            ]
        );
    }
}
