<?php

use App\Filament\StaticPages\Blocks\HeroBlock;
use App\Filament\StaticPages\Blocks\NewContentBlock;
use App\Filament\StaticPages\SubBlocks\PhotoTexteSubBlock;
use App\Filament\StaticPages\SubBlocks\TextePhotoSubBlock;
use App\Filament\StaticPages\SubBlocks\TexteTexteSubBlock;
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
        NewContentBlock::class,
    ],

    'sub_blocks' => [
        TextePhotoSubBlock::class,
        PhotoTexteSubBlock::class,
        TexteTexteSubBlock::class,
    ],

    'rich_editor' => [
        'plugins' => [
            PageLinkPlugin::class,
            OrderedListPlugin::class,
        ],
    ],
];
