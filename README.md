# filament-static-pages

Plugin [Filament 5](https://filamentphp.com) pour gérer des **pages statiques avec un éditeur de blocs** dans une application Laravel.

Le package fournit toute l'infrastructure (modèle, ressource Filament, Livewire, registres de blocs) tandis que les blocs, les vues et les classes applicatives sont **publiés dans votre application** via la commande d'installation.

---

## Prérequis

| Dépendance | Version |
|---|---|
| PHP | `^8.3` |
| Laravel | `^13.0` |
| Livewire | `^4.0` |
| Filament | `^5.0` |
| filament/spatie-laravel-settings-plugin | `^5.0` |

---

## Installation

```bash
composer require charlesstolive/filament-static-pages
```

Lancez ensuite la commande d'installation :

```bash
php artisan filament-static-pages:install
```

### Options disponibles

| Option | Description |
|---|---|
| `--force` | Écrase les fichiers déjà existants dans l'application |
| `--skip-migrations` | Saute la question d'exécution des migrations |

---

## Ce que publie `filament-static-pages:install`

La commande publie dans votre application, sans écraser les fichiers existants (sauf avec `--force`) :

### Classes PHP

| Fichier publié | Chemin destination |
|---|---|
| `AdminSettings.php` | `app/Settings/AdminSettings.php` |
| `AdminSettingsPage.php` | `app/Filament/Pages/AdminSettingsPage.php` |
| `HeroBlock.php` | `app/Filament/StaticPages/Blocks/HeroBlock.php` |
| `NewContentBlock.php` | `app/Filament/StaticPages/Blocks/NewContentBlock.php` |
| `TextePhotoSubBlock.php` | `app/Filament/StaticPages/SubBlocks/TextePhotoSubBlock.php` |
| `PhotoTexteSubBlock.php` | `app/Filament/StaticPages/SubBlocks/PhotoTexteSubBlock.php` |
| `TexteTexteSubBlock.php` | `app/Filament/StaticPages/SubBlocks/TexteTexteSubBlock.php` |

### Migrations

| Fichier | Destination |
|---|---|
| Migration Spatie Settings (table `settings`) | `database/migrations/` |
| Migrations CMS (table `cms_pages`) | `database/migrations/` |
| `create_admin_settings.php` (horodatée) | `database/settings/` |

La migration admin settings est ignorée si une migration `*admin_settings*` existe déjà dans `database/settings/`.

### Vues

```
resources/views/
├── layouts/
│   ├── front.blade.php          ← layout principal du front
│   └── construction.blade.php   ← layout mode maintenance
├── partials/
│   ├── header.blade.php         ← en-tête (à personnaliser)
│   └── footer.blade.php         ← pied de page
├── livewire/front/
│   └── construction-page.blade.php
├── filament/widgets/
│   └── construction-mode-widget.blade.php
└── components/filament-static-pages/
    ├── blocks/
    │   ├── hero.blade.php
    │   ├── new-content.blade.php
    │   └── shared/
    │       ├── section.blade.php
    │       ├── title.blade.php
    │       ├── description.blade.php
    │       ├── button-group.blade.php
    │       ├── html-reader.blade.php
    │       └── photo-display.blade.php
    └── blocks/sub/
        ├── texte-photo.blade.php
        ├── photo-texte.blade.php
        └── texte-texte.blade.php
```

### Configuration, CSS & JS

| Fichier | Destination |
|---|---|
| `config/filament-static-pages.php` | `config/filament-static-pages.php` |
| `filament-static-pages.css` | `resources/css/filament-static-pages.css` |
| `js/front/` (index.js, FrontApp, animations, Alpine) | `resources/js/front/` |

---

## Enregistrement du plugin

Dans votre `PanelProvider` :

```php
use CharlesStOlive\FilamentStaticPages\FilamentStaticPagesPlugin;
use CharlesStOlive\FilamentStaticPages\Filament\Widgets\ConstructionModeWidget;
use App\Filament\Pages\AdminSettingsPage;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugin(FilamentStaticPagesPlugin::make())
        ->pages([AdminSettingsPage::class])
        ->widgets([ConstructionModeWidget::class]);
}
```

Pour désactiver la ressource Filament intégrée :

```php
FilamentStaticPagesPlugin::make()->pageResource(false)
```

---

## Configuration

```php
// config/filament-static-pages.php

return [
    'table_name' => 'cms_pages',

    'model' => Page::class,

    'route' => [
        'enabled' => true,
        'prefix'  => 'pages',
        'name'    => 'page',
        'middleware' => ['web'],
    ],

    'front' => [
        'layout' => 'layouts.front',
    ],

    'filament' => [
        'register_resource'  => true,
        'navigation_group'   => 'CMS',
        'navigation_label'   => 'Pages',
        'navigation_icon'    => 'heroicon-o-rectangle-stack',
    ],

    // Blocs disponibles dans l'éditeur
    'blocks' => [
        HeroBlock::class,
        NewContentBlock::class,
    ],

    // Sous-blocs pour NewContentBlock
    'sub_blocks' => [
        TextePhotoSubBlock::class,
        PhotoTexteSubBlock::class,
        TexteTexteSubBlock::class,
    ],

    // Plugins RichEditor actifs
    'rich_editor' => [
        'plugins' => [
            PageLinkPlugin::class,
            OrderedListPlugin::class,
        ],
    ],
];
```

---

## Créer un bloc personnalisé

Créez une classe dans `app/Filament/StaticPages/Blocks/` qui étend `PageBlock` :

```php
namespace App\Filament\StaticPages\Blocks;

use CharlesStOlive\FilamentStaticPages\Blocks\PageBlock;
use CharlesStOlive\FilamentStaticPages\Blocks\Concerns\HasPageBlockFields;
use Filament\Forms\Components\TextInput;

class MonBlock extends PageBlock
{
    use HasPageBlockFields;

    public static function type(): string
    {
        return 'mon-block';
    }

    public static function label(): string
    {
        return 'Mon bloc';
    }

    public static function schema(): array
    {
        return [
            TextInput::make('titre')->label('Titre'),
            static::fullEditor('contenu'),
        ];
    }
}
```

Puis publiez la vue correspondante dans :
`resources/views/components/filament-static-pages/blocks/mon-block.blade.php`

Et déclarez le bloc dans la config :

```php
'blocks' => [
    MonBlock::class,
],
```

---

## Créer un sous-bloc personnalisé

Créez une classe dans `app/Filament/StaticPages/SubBlocks/` qui implémente `SubBlockContract` :

```php
namespace App\Filament\StaticPages\SubBlocks;

use CharlesStOlive\FilamentStaticPages\Blocks\SubBlocks\Contracts\SubBlockContract;
use Filament\Forms\Components\TextInput;

class MonSousBloc implements SubBlockContract
{
    public static function type(): string   { return 'mon-sous-bloc'; }
    public static function label(): string  { return 'Mon sous-bloc'; }
    public static function schema(): array  { return [TextInput::make('texte')]; }
    public static function view(): string
    {
        return 'components.filament-static-pages.blocks.sub.mon-sous-bloc';
    }
}
```

Publiez la vue dans :
`resources/views/components/filament-static-pages/blocks/sub/mon-sous-bloc.blade.php`

Déclarez le sous-bloc dans la config :

```php
'sub_blocks' => [
    MonSousBloc::class,
],
```

---

## CSS, JS & Tailwind v4

Le fichier `resources/css/filament-static-pages.css` publié contient les classes utilitaires front (boutons, animations, effets de masque).

Dans votre fichier CSS d'entrée principal (ex. `resources/css/front.css`) :

```css
/* Scan des vues du package pour les classes Tailwind */
@source "../../../vendor/charlesstolive/filament-static-pages/resources/views/**/*.blade.php";

@import "./sp/filament-static-pages.css";
```

> **Important** : `@source` doit être dans le fichier d'entrée racine déclaré dans `vite.config.js`, pas dans un fichier importé.

### JS front

Le dossier `resources/js/front/` publié contient l'app Alpine (animations, composants, FrontApp).

Dans votre `vite.config.js`, ajoutez l'entrée :

```js
input: [
    // ... vos entrées existantes
    'resources/js/front/index.js',
],
```

---

## Mode construction

Le widget `ConstructionModeWidget` (fourni directement par le plugin, namespace `CharlesStOlive\FilamentStaticPages\Filament\Widgets`) permet d'activer/désactiver le mode maintenance depuis le dashboard Filament.

Il s'appuie sur `AdminSettings::construction` (champ `array` avec `enabled`, `titre`, `description`).

Pour intercepter les requêtes front en mode maintenance, ajoutez un middleware :

```php
// app/Http/Middleware/CheckConstructionMode.php
if ($settings->construction['enabled'] ?? false) {
    return redirect()->route('construction');
}
```

---

## Plugins RichEditor intégrés

| Plugin | Description |
|---|---|
| `PageLinkPlugin` | Lien vers une page interne publiée (liste déroulante auto-alimentée) |
| `OrderedListPlugin` | Liste ordonnée avec numéro de départ personnalisable |

Ces plugins sont automatiquement disponibles dans `HasPageBlockFields::fullEditor()` selon la config `rich_editor.plugins`.

---

## Licence

MIT
