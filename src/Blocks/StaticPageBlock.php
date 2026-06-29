<?php

namespace CharlesStOlive\FilamentStaticPages\Blocks;

use Filament\Forms\Components\Builder\Block;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class StaticPageBlock extends Block
{
    public function renderPreview(array $data): View
    {
        $key = (string) Str::uuid();
        $frameId = 'static-page-preview-' . Str::uuid()->toString();

        Cache::put(
            "static-page-preview-block:{$key}",
            [
                'type' => $this->getName(),
                'view' => $this->evaluate($this->preview),
                'data' => $data,
                'frame_id' => $frameId,
            ],
            now()->addMinutes(10),
        );

        return view('filament-static-pages::preview.iframe', [
            'frameId' => $frameId,
            'src' => URL::temporarySignedRoute(
                'filament-static-pages.preview.block',
                now()->addMinutes(10),
                ['key' => $key],
            ),
        ]);
    }
}
