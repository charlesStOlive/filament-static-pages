<?php

namespace CharlesStOlive\FilamentStaticPages\Filament\Resources\Pages\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use CharlesStOlive\FilamentStaticPages\Filament\Resources\Pages\PageResource;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        Log::info('[FilamentStaticPages] CreatePage::mutateFormDataBeforeCreate — début création', [
            'titre'  => $data['titre'] ?? '(vide)',
            'slug'   => $data['slug'] ?? '(vide)',
            'status' => $data['status'] ?? '(vide)',
        ]);

        return $data;
    }

    protected function afterCreate(): void
    {
        Log::info('[FilamentStaticPages] CreatePage::afterCreate — création réussie', [
            'record_id' => $this->record?->getKey(),
        ]);
    }
}
