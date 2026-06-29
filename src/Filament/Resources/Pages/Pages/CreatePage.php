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

    protected function onValidationError(\Illuminate\Validation\ValidationException $exception): void
    {
        Log::warning('[FilamentStaticPages] CreatePage::onValidationError — erreur de validation', [
            'errors' => $exception->errors(),
        ]);

        $lines = collect($exception->errors())
            ->flatMap(fn (array $messages, string $path) => collect($messages)->map(
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
