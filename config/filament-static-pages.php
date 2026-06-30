<?php

use CharlesStOlive\FilamentStaticPages\Models\Page;
use CharlesStOlive\FilamentStaticPages\RichEditor\Plugins\OrderedListPlugin;
use CharlesStOlive\FilamentStaticPages\RichEditor\Plugins\PageLinkPlugin;

return [
    'table_name' => 'cms_pages',

    'model' => Page::class,

    'route' => [
        'enabled' => true,
        'prefix' => 'pages',
        'name' => 'page',
        'middleware' => ['web'],
        'use_static_page_as_home_page' => true,
        'use_construction_page' => true,
    ],

    'front' => [
        'layout' => 'layouts.front',
        'view' => 'filament-static-pages::livewire.static-page',
    ],

    /*
    |--------------------------------------------------------------------------
    | Navigation front
    |--------------------------------------------------------------------------
    |
    | Ces vues sont celles de l'application finale.
    | Le plugin leur injectera :
    | - $headerPages
    | - $footerPages
    | - $siteLogo
    |
    */
    'navigation' => [
        'enabled' => true,

        'views' => [
            'layouts.front',
            'partials.header',
            'partials.footer',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Settings de l'application hôte
    |--------------------------------------------------------------------------
    |
    */
    'settings' => [
        'class' => \App\Settings\AdminSettings::class,
        'logo_key' => 'logo',
    ],

    'filament' => [
        'register_resource' => true,
        'navigation_group' => 'CMS',
        'navigation_label' => 'Pages',
        'navigation_icon' => 'heroicon-o-rectangle-stack',
        'settings_page' => null, // e.g. \App\Filament\Pages\AdminSettingsPage::class
    ],

    // Populated by: php artisan filament-static-pages:install
    'blocks' => [],

    // Populated by: php artisan filament-static-pages:install
    'sub_blocks' => [],

    'rich_editor' => [
        'plugins' => [
            PageLinkPlugin::class,
            OrderedListPlugin::class,
        ],
    ],
];
