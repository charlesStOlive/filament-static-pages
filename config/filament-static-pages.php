<?php

use Notilac\FilamentStaticPages\Blocks\ContentBlock;
use Notilac\FilamentStaticPages\Blocks\HeroBlock;
use Notilac\FilamentStaticPages\Blocks\NewContentBlock;
use Notilac\FilamentStaticPages\Models\Page;

return [
    'table_name' => 'cms_pages',

    'model' => Page::class,

    'route' => [
        'enabled' => true,
        'prefix' => 'pages',
        'name' => 'page',
        'middleware' => ['web'],
    ],

    'front' => [
        'layout' => 'layouts.front',
        'view' => 'filament-static-pages::livewire.static-page',
    ],

    'filament' => [
        'register_resource' => true,
        'navigation_group' => 'CMS',
        'navigation_label' => 'Pages',
        'navigation_icon' => 'heroicon-o-rectangle-stack',
    ],

    'blocks' => [
        HeroBlock::class,
        ContentBlock::class,
        NewContentBlock::class,
    ],

    'rich_editor' => [
        'plugins' => [
            // À compléter plus tard si tu migres PageLinkPlugin / OrderedListPlugin dans le package.
        ],
    ],
];
