<?php

namespace CharlesStOlive\FilamentStaticPages\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'filament-static-pages:install
                            {--force : Écrase les fichiers existants}
                            {--skip-migrations : Ne pas exécuter les migrations}';

    protected $description = 'Installe filament-static-pages : publie les blocs, les settings, les layouts et le CSS dans votre application';

    public function handle(): int
    {
        $this->components->info('Installation de filament-static-pages...');
        $this->newLine();

        // ── 1. Migrations Spatie Settings ─────────────────────────────────────
        $this->components->task('Publication de la migration Spatie Settings', function () {
            $this->callSilently('vendor:publish', [
                '--provider' => 'Spatie\LaravelSettings\LaravelSettingsServiceProvider',
                '--tag'      => 'migrations',
                '--force'    => $this->option('force'),
            ]);
        });

        // ── 2. Migrations du package ──────────────────────────────────────────
        $this->components->task('Publication des migrations CMS', function () {
            $this->callSilently('vendor:publish', [
                '--tag'   => 'filament-static-pages-migrations',
                '--force' => $this->option('force'),
            ]);
        });

        // ── 3. Migration settings admin ───────────────────────────────────────
        $this->components->task('Publication de la migration Admin Settings', function () {
            $filename = date('Y_m_d_His') . '_create_admin_settings.php';
            $target   = database_path('settings/' . $filename);
            File::ensureDirectoryExists(database_path('settings'));
            if ($this->option('force') || ! $this->adminSettingsMigrationExists()) {
                File::copy(__DIR__ . '/../../stubs/database/settings/create_admin_settings.php', $target);
            }
        });

        // ── 4. PHP — Settings ─────────────────────────────────────────────────
        $this->components->task('Publication de la classe AdminSettings', function () {
            $this->publishFile(
                __DIR__ . '/../../stubs/Settings/AdminSettings.php',
                app_path('Settings/AdminSettings.php'),
            );
        });

        // ── 5. PHP — Filament Pages ───────────────────────────────────────────
        $this->components->task('Publication de AdminSettingsPage', function () {
            $this->publishFile(
                __DIR__ . '/../../stubs/Filament/Pages/AdminSettingsPage.php',
                app_path('Filament/Pages/AdminSettingsPage.php'),
            );
        });

        // ── 7. PHP — Blocks ───────────────────────────────────────────────────
        $this->components->task('Publication des classes PHP (Blocks)', function () {
            $this->publishDirectory(
                __DIR__ . '/../../stubs/Blocks',
                app_path('Filament/StaticPages/Blocks'),
            );
        });

        // ── 8. PHP — SubBlocks ────────────────────────────────────────────────
        $this->components->task('Publication des classes PHP (SubBlocks)', function () {
            $this->publishDirectory(
                __DIR__ . '/../../stubs/SubBlocks',
                app_path('Filament/StaticPages/SubBlocks'),
            );
        });

        // ── 9. Views — Layouts ────────────────────────────────────────────────
        $this->components->task('Publication des layouts', function () {
            $this->publishDirectory(
                __DIR__ . '/../../stubs/views/layouts',
                resource_path('views/layouts'),
            );
        });

        // ── 10. Views — Partials ──────────────────────────────────────────────
        $this->components->task('Publication des partials (header, footer)', function () {
            $this->publishDirectory(
                __DIR__ . '/../../stubs/views/partials',
                resource_path('views/partials'),
            );
        });

        // ── 11. Views — Livewire construction ────────────────────────────────
        $this->components->task('Publication de la page construction', function () {
            $this->publishDirectory(
                __DIR__ . '/../../stubs/views/livewire',
                resource_path('views/livewire'),
            );
        });

        // ── 11b. PHP — Livewire ContactForm ──────────────────────────────────
        $this->components->task('Publication du composant Livewire (ContactForm)', function () {
            $this->publishFile(
                __DIR__ . '/../../stubs/Livewire/ContactForm.php',
                app_path('Livewire/ContactForm.php'),
            );
        });

        // ── 11c. Views — Emails ───────────────────────────────────────────────
        $this->components->task('Publication des vues emails', function () {
            $this->publishDirectory(
                __DIR__ . '/../../stubs/views/emails',
                resource_path('views/emails'),
            );
        });

        // ── 11d. Images front ─────────────────────────────────────────────────
        $this->components->task('Publication des images front', function () {
            $this->publishDirectory(
                __DIR__ . '/../../resources/images/front',
                resource_path('images/front'),
            );
        });

        // ── 12. Views — Filament widget ───────────────────────────────────────
        $this->components->task('Publication du widget Filament', function () {
            $this->publishDirectory(
                __DIR__ . '/../../stubs/views/filament',
                resource_path('views/filament'),
            );
        });

        // ── 13. Views — Blocks ────────────────────────────────────────────────
        $this->components->task('Publication des views de blocs', function () {
            $this->publishDirectory(
                __DIR__ . '/../../stubs/views/components',
                resource_path('views/components'),
            );
        });

        // ── 14. CSS stub ──────────────────────────────────────────────────────
        $this->components->task('Publication du CSS', function () {
            $this->publishFile(
                __DIR__ . '/../../stubs/css/front/filament-static-pages.css',
                resource_path('css/front/sp/filament-static-pages.css'),
            );
        });

        // ── 15. JS front ──────────────────────────────────────────────────────
        $this->components->task('Publication du JS front', function () {
            $this->publishDirectory(
                __DIR__ . '/../../stubs/js/front',
                resource_path('js/front'),
            );
        });

        // ── 16. Config (avec les classes App\) ────────────────────────────────
        $this->components->task('Publication de la configuration', function () {
            File::copy(
                __DIR__ . '/../../stubs/config/filament-static-pages.php',
                config_path('filament-static-pages.php'),
            );
        });

        // ── 16. Migrations ────────────────────────────────────────────────────
        if (! $this->option('skip-migrations') && $this->components->confirm('Exécuter les migrations maintenant ?', true)) {
            $this->call('migrate');
        }

        $this->newLine();
        $this->components->success('filament-static-pages installé avec succès !');
        $this->newLine();

        $this->components->bulletList([
            'app/Settings/AdminSettings.php',
            'app/Filament/Pages/AdminSettingsPage.php',
            'app/Filament/StaticPages/Blocks/ (HeroBlock, NewContentBlock)',
            'app/Filament/StaticPages/SubBlocks/ (3 sub-blocs)',
            'resources/views/layouts/ (front, construction)',
            'resources/views/partials/ (header, footer)',
            'resources/views/livewire/front/construction-page.blade.php',
            'app/Livewire/ContactForm.php',
            'resources/views/emails/contact.blade.php',
            'resources/images/front/ (svgs, masks webp)',
            'resources/views/components/filament-static-pages/ (blocks + shared + sub)',
            'resources/css/front/sp/filament-static-pages.css',
            'resources/js/front/ (index.js, FrontApp, animations, Alpine)',
            'config/filament-static-pages.php',
        ]);

        $this->newLine();
        $this->line('  <comment>Prochaines étapes :</comment>');
        $this->line('  1. Ajoutez dans votre CSS d\'entrée Vite :');
        $this->line('       <info>@source "…/vendor/charlesstolive/filament-static-pages/resources/views/**/*.blade.php";</info>');
        $this->line('       <info>@import "./sp/filament-static-pages.css";</info>');
        $this->line('  2. Ajoutez dans votre vite.config.js :');
        $this->line('       <info>\'resources/js/front/index.js\'</info>');
        $this->line('  3. Enregistrez dans votre PanelProvider :');
        $this->line('       <info>FilamentStaticPagesPlugin::make()</info>');
        $this->line('       <info>->constructionWidget() // active le widget de mode construction</info>');

        return self::SUCCESS;
    }

    protected function publishFile(string $source, string $target): void
    {
        if ($this->option('force') || ! File::exists($target)) {
            File::ensureDirectoryExists(dirname($target));
            File::copy($source, $target);
        }
    }

    protected function publishDirectory(string $source, string $target): void
    {
        File::ensureDirectoryExists($target);

        foreach (File::allFiles($source) as $file) {
            $relativePath = $file->getRelativePathname();
            $targetPath   = $target . DIRECTORY_SEPARATOR . $relativePath;

            File::ensureDirectoryExists(dirname($targetPath));

            if (! $this->option('force') && File::exists($targetPath)) {
                continue;
            }

            File::copy($file->getPathname(), $targetPath);
        }
    }

    protected function adminSettingsMigrationExists(): bool
    {
        $files = File::glob(database_path('settings/*admin_settings*'));

        return ! empty($files);
    }
}
