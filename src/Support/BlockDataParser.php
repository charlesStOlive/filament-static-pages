<?php

namespace CharlesStOlive\FilamentStaticPages\Support;

use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Illuminate\Support\Facades\Storage;

class BlockDataParser
{
    public function __construct(
        private string $mode = 'front',
        private mixed $page = null,
    ) {}

    public static function fromBlockData(array $blockData, string $mode = 'front', mixed $page = null): array
    {
        return (new static($mode, $page))->processAllData($blockData);
    }

    private function processAllData(array $blockData): array
    {
        $processed = [];

        foreach ($blockData as $key => $value) {
            $processed[$key] = $this->processDataValue((string) $key, $value);
        }

        return $processed;
    }

    private function processDataValue(string $key, mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return $value;
        }

        if (is_array($value) && $value === []) {
            return $value;
        }

        // Champs HTML / RichEditor
        if (str_starts_with($key, 'html_')) {
            return $this->processHtmlContent($value);
        }

        // Champs image simple
        if (str_starts_with($key, 'image_')) {
            return $this->processImageUrl($value);
        }

        // Champs images multiples
        if (str_starts_with($key, 'images_')) {
            return $this->processMultipleImages($value);
        }

        // Tableaux imbriqués : photo_config, background_datas, ambiance, etc.
        if (is_array($value) && $this->isAssociativeArray($value)) {
            return collect($value)
                ->mapWithKeys(fn($subValue, $subKey) => [
                    $subKey => $this->processDataValue((string) $subKey, $subValue),
                ])
                ->all();
        }

        // Listes indexées : on traite récursivement les arrays internes si besoin.
        if (is_array($value)) {
            return collect($value)
                ->map(function ($item) {
                    if (is_array($item)) {
                        return $this->processAllData($item);
                    }

                    return $item;
                })
                ->all();
        }

        return $value;
    }

    private function processImageUrl(mixed $image): ?string
    {
        if (blank($image)) {
            return null;
        }

        /*
         * Cas Livewire / Filament upload temporaire.
         * Utile quand le fichier existe dans l'état du formulaire
         * mais que le modèle n'est pas encore sauvegardé.
         */
        if (is_object($image) && method_exists($image, 'temporaryUrl')) {
            return $image->temporaryUrl();
        }

        /*
         * Cas Filament FileUpload stocké sous forme :
         * [
         *     'uuid' => 'pages/photos/image.webp'
         * ]
         *
         * Même pour une seule image.
         */
        if (is_array($image)) {
            $image = collect($image)
                ->filter(fn($value) => filled($value))
                ->first();

            if (blank($image)) {
                return null;
            }

            if (is_object($image) && method_exists($image, 'temporaryUrl')) {
                return $image->temporaryUrl();
            }

            if (is_array($image)) {
                $image = collect($image)
                    ->filter(fn($value) => filled($value))
                    ->first();
            }
        }

        if (is_object($image) && method_exists($image, '__toString')) {
            $image = (string) $image;
        }

        if (! is_string($image) || blank($image)) {
            return null;
        }

        // URL absolue
        if (
            str_starts_with($image, 'http://') ||
            str_starts_with($image, 'https://')
        ) {
            return $image;
        }

        // URL déjà résolue
        if (str_starts_with($image, '/')) {
            return $image;
        }

        // Chemin relatif au disque public
        return Storage::disk('public')->url($image);
    }

    private function processMultipleImages(mixed $images): ?array
    {
        if (blank($images)) {
            return null;
        }

        if (! is_array($images)) {
            $images = [$images];
        }

        $processed = [];

        foreach ($images as $image) {
            $url = $this->processImageUrl($image);

            if ($url) {
                $processed[] = $url;
            }
        }

        return $processed ?: null;
    }

    private function processHtmlContent(mixed $content): ?string
    {
        if (! $content) {
            return null;
        }

        if (is_string($content)) {
            return $this->cleanInternalLinks($content);
        }

        if (is_array($content)) {
            $renderer = RichContentRenderer::make($content);

            foreach (config('filament-static-pages.rich_editor.plugins', []) as $pluginClass) {
                $renderer->plugins([
                    $pluginClass::make(),
                ]);
            }

            return $this->cleanInternalLinks($renderer->toHtml());
        }

        return null;
    }

    private function isAssociativeArray(array $array): bool
    {
        return $array !== [] && array_keys($array) !== range(0, count($array) - 1);
    }

    public static function extractDataFromBladeVars(array $vars): array
    {
        $systemVars = [
            '__env',
            '__data',
            'obLevel',
            '__path',
            'app',
            'errors',
            'settings',
            'user',
            'component',
            'attributes',
            'slot',
        ];

        $extractedData = array_diff_key($vars, array_flip($systemVars));

        return (new static('preview'))->processAllData($extractedData);
    }

    private function cleanInternalLinks(string $content): string
    {
        return preg_replace_callback(
            '/<a([^>]*?)href=["\'](\/[^"\']*|#[^"\']*)["\']([^>]*?)>/i',
            function ($matches) {
                $beforeHref = preg_replace('/\s*target=["\']_blank["\']/i', '', $matches[1]);
                $afterHref = preg_replace('/\s*target=["\']_blank["\']/i', '', $matches[3]);

                $beforeHref = preg_replace('/\s*rel=["\'][^"\']*["\']/i', '', $beforeHref);
                $afterHref = preg_replace('/\s*rel=["\'][^"\']*["\']/i', '', $afterHref);

                return '<a' . $beforeHref . 'href="' . $matches[2] . '"' . $afterHref . '>';
            },
            $content
        );
    }
}
