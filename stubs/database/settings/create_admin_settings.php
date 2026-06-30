<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('admin_cms.email', 'admin@example.com');
        $this->migrator->add('admin_cms.telephone', '+33 1 23 45 67 89');
        $this->migrator->add('admin_cms.adresse', '');
        $this->migrator->add('admin_cms.horaire', '');
        $this->migrator->add('admin_cms.mailRecepteur', 'contact@example.com');
        $this->migrator->add('admin_cms.logo', null);
        $this->migrator->add('admin_cms.footerText', 'Copyright © ' . date('Y') . '. Tous droits réservés.');
        $this->migrator->add('admin_cms.construction', [
            'activate'    => false,
            'titre'       => 'Site en maintenance',
            'description' => 'Nous travaillons actuellement sur notre site. Nous serons de retour bientôt !',
        ]);
    }
};
