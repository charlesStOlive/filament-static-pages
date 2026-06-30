<?php

namespace CharlesStOlive\FilamentStaticPages\Filament\Resources\Pages\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;
use CharlesStOlive\FilamentStaticPages\Filament\Resources\Pages\PageResource;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            $this->getSaveFormAction()->color('success')->label('Sauvegarder')->formId('form'),
            DeleteAction::make()


        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        Log::info('[FilamentStaticPages] EditPage::mutateFormDataBeforeSave — début sauvegarde', [
            'record_id' => $this->record?->getKey(),
            'titre'     => $data['titre'] ?? '(vide)',
            'slug'      => $data['slug'] ?? '(vide)',
            'status'    => $data['status'] ?? '(vide)',
            'contents_count' => count($data['contents'] ?? []),
        ]);

        return $data;
    }

    protected function afterSave(): void
    {
        Log::info('[FilamentStaticPages] EditPage::afterSave — enregistrement réussi', [
            'record_id' => $this->record?->getKey(),
        ]);
    }
}
