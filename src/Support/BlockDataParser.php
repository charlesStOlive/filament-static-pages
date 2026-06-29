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
            $processed[$key] = $this->processDataValue($key, $value);
        }

        return $processed;
    }

    private function processDataValue(string $key, mixed $value): mixed
    {
        if ($value === null || (is_array($value) && empty($value))) {
            return $value;
        }

        if (str_starts_with($key, 'image_')) {
            return $this->processImageUrl($value);
        }

        if (str_starts_with($key, 'images_')) {
            return $this->processMultipleImages($value);
        }

        if (str_starts_with($key, 'html_')) {
            return $this->processHtmlContent($value);
        }

        if (is_array($value) && $this->isAssociativeArray($value)) {
            return collect($value)
                ->mapWithKeys(fn($subValue, $subKey) => [
                    $subKey => $this->processDataValue($subKey, $subValue),
                ])
                ->all();
        }

        return $value;
    }

    private function processImageUrl(mixed $image): ?string
    {
        if (! $image) {
            return null;
        }

        if (is_string($image)) {
            if (str_starts_with($image, 'http')) {
                return $image;
            }

            return Storage::disk('public')->url($image);
        }

        if ($this->mode === 'preview' && is_object($image) && method_exists($image, 'temporaryUrl')) {
            return $image->temporaryUrl();
        }

        return null;
    }

    private function processMultipleImages(mixed $images): ?array
    {
        if (! is_array($images)) {
            return null;
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

    /**
     * Extrait et traite les données depuis les variables Blade disponibles.
     *
     * Utilisé en mode "preview" Filament : quand Filament rend un bloc en
     * prévisualisation, il passe chaque champ directement comme variable Blade
     * (pas de $block['data']). Cette méthode reconstruit le tableau de données
     * attendu par les vues à partir de ces variables individuelles.
     *
     * Variables système Blade (exclues de l'extraction) : __env, __data,
     * obLevel, app, errors, component, attributes, slot, etc.
     *
     * @param array $vars Résultat de get_defined_vars() dans la vue Blade
     * @return array Données traitées (images → URL, html_ → HTML rendu)
     */
    public static function extractDataFromBladeVars(array $vars): array
    {
        // Variables internes Blade/Laravel à ignorer
        $systemVars = [
            '__env', '__data', 'obLevel', '__path',
            'app', 'errors', 'settings', 'user',
            'component', 'attributes', 'slot',
        ];

        $extractedData = array_diff_key($vars, array_flip($systemVars));

        // Traiter les données extraites comme des données de bloc normales
        // mais en mode 'preview' pour que les images utilisent temporaryUrl()
        $instance = new static('preview');

        return $instance->processAllData($extractedData);
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
