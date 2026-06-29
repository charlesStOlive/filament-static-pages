<?php

namespace CharlesStOlive\FilamentStaticPages\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PreviewBlockController
{
    public function __invoke(Request $request, string $key)
    {
        $payload = Cache::get("static-page-preview-block:{$key}");

        abort_unless($payload, 404);

        return response()
            ->view('filament-static-pages::preview.document', [
                'blockView' => $payload['view'],
                'block' => [
                    'type' => $payload['type'],
                    'data' => $payload['data'],
                ],
                'mode' => 'preview',
                'page' => null,
                'frameId' => $payload['frame_id'],
                'parentOrigin' => $request->getSchemeAndHttpHost(),
            ])
            ->header('X-Robots-Tag', 'noindex');
    }
}
