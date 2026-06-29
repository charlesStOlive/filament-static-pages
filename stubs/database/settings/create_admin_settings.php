<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('admin.email', 'admin@example.com');
        $this->migrator->add('admin.telephone', '+33 1 23 45 67 89');
        $this->migrator->add('admin.adresse', '');
        $this->migrator->add('admin.horaire', '');
        $this->migrator->add('admin.mailRecepteur', 'contact@example.com');
        $this->migrator->add('admin.logo', null);
        $this->migrator->add('admin.footerText', 'Copyright © ' . date('Y') . '. Tous droits réservés.');
        $this->migrator->add('admin.construction', [
            'activate'    => false,
            'titre'       => 'Site en maintenance',
            'description' => 'Nous travaillons actuellement sur notre site. Nous serons de retour bientôt !',
        ]);
    }
};
