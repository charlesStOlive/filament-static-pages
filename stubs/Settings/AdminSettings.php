<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class AdminSettings extends Settings
{
    // Informations de contact
    public string $email;
    public string $telephone;
    public string $adresse;
    public string $horaire;
    public string $mailRecepteur;

    // Apparence
    public ?string $logo;
    public string $footerText;

    // Mode maintenance/construction
    public array $construction;

    public static function group(): string
    {
        return 'admin_cms';
    }

    public static function encrypted(): array
    {
        return [];
    }
}
