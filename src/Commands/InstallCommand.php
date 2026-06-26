<?php

namespace CharlesStOlive\FilamentStaticPages\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'filament-static-pages:install
                            {--force : Écrase les fichiers existants}
                            {--skip-colors : Ne pas appeler filament-colors:install}
                            {--skip-migrations : Ne pas exécuter les migrations}';

    protected $description = 'Installe filament-static-pages : publie les blocs, les views et le CSS dans votre application';

    public function handle(): int
    {
        $this->components->info('Installation de filament-static-pages...');
        $this->newLine();

        // ── 1. filament-color-installer ───────────────────────────────────────
        if (! $this->option('skip-colors') && $this->colorInstallerAvailable()) {
            $this->components->task('Configuration du système de couleurs', function () {
                $this->callSilently('filament-colors:install');
            });
        }

        // ── 2. Migrations ─────────────────────────────────────────────────────
        $this->components->task('Publication des migrations', function () {
            $this->callSilently('vendor:publish', [
                '--tag'   => 'filament-static-pages-migrations',
                '--force' => $this->option('force'),
            ]);
        });

        // ── 3. PHP — Blocks ───────────────────────────────────────────────────
        $this->components->task('Publication des classes PHP (Blocks)', function () {
            $this->publishDirectory(
                __DIR__ . '/../../stubs/Blocks',
                app_path('Filament/StaticPages/Blocks'),
            );
        });

        // ── 4. PHP — SubBlocks ────────────────────────────────────────────────
        $this->components->task('Publication des classes PHP (SubBlocks)', function () {
            $this->publishDirectory(
                __DIR__ . '/../../stubs/SubBlocks',
                app_path('Filament/StaticPages/SubBlocks'),
            );
        });

        // ── 5. Views ─────────────────────────────────────────────────────────
        $this->components->task('Publication des views', function () {
            $this->publishDirectory(
                __DIR__ . '/../../stubs/views',
                resource_path('views'),
            );
        });

        // ── 6. CSS stub ───────────────────────────────────────────────────────
        $this->components->task('Publication du CSS', function () {
            $target = resource_path('css/filament-static-pages.css');

            if ($this->option('force') || ! File::exists($target)) {
                File::ensureDirectoryExists(resource_path('css'));
                File::copy(__DIR__ . '/../../stubs/css/filament-static-pages.css', $target);
            }
        });

        // ── 7. Config (avec les classes App\) ────────────────────────────────
        $this->components->task('Publication de la configuration', function () {
            File::copy(
                __DIR__ . '/../../stubs/config/filament-static-pages.php',
                config_path('filament-static-pages.php'),
            );
        });

        // ── 8. Migrations ─────────────────────────────────────────────────────
        if (! $this->option('skip-migrations') && $this->components->confirm('Exécuter les migrations maintenant ?', true)) {
            $this->call('migrate');
        }

        $this->newLine();
        $this->components->success('filament-static-pages installé avec succès !');
        $this->newLine();

        $this->components->bulletList([
            'Classes publiées dans <comment>app/Filament/StaticPages/</comment>',
            'Views publiées dans <comment>resources/views/components/filament-static-pages/</comment>',
            'CSS publié dans <comment>resources/css/filament-static-pages.css</comment>',
        ]);

        $this->newLine();
        $this->line('  <comment>Prochaines étapes :</comment>');
        $this->line('  1. Ajoutez dans votre fichier CSS Tailwind (entrée Vite) :');
        $this->line('       <info>@source "../../../vendor/charlesstolive/filament-static-pages/resources/views/**/*.blade.php";</info>');
        $this->line('       <info>@import "./filament-static-pages.css";</info>');
        $this->line('  2. Enregistrez le plugin dans votre PanelProvider :');
        $this->line('       <info>FilamentStaticPagesPlugin::make()</info>');

        return self::SUCCESS;
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

    protected function colorInstallerAvailable(): bool
    {
        return class_exists(
            \CharlesStOlive\FilamentColorInstaller\Providers\FilamentColorInstallerServiceProvider::class
        );
    }
}
