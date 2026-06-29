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
            DeleteAction::make(),
        ];
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

    protected function onValidationError(\Illuminate\Validation\ValidationException $exception): void
    {
        Log::warning('[FilamentStaticPages] EditPage::onValidationError — erreur de validation', [
            'errors' => $exception->errors(),
        ]);

        // Filament ne peut pas surligner les champs qui sont dans un modal de bloc fermé.
        // On construit donc une notification lisible à partir des messages de validation.
        $lines = collect($exception->errors())
            ->flatMap(fn (array $messages, string $path) => collect($messages)->map(
                // Extraire le nom du champ depuis le chemin imbriqué (ex: data.contents.{uuid}.data.background_datas.mode)
                fn (string $msg) => '<strong>' . last(explode('.', $path)) . '</strong> : ' . $msg
            ))
            ->unique()
            ->implode('<br>');

        \Filament\Notifications\Notification::make()
            ->title('Impossible de sauvegarder — champ(s) invalide(s)')
            ->body($lines)
            ->danger()
            ->persistent()
            ->send();
    }
}
