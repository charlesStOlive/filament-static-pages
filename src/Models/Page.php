<?php

namespace Notilac\FilamentStaticPages\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'titre',
        'meta_data',
        'contents',
        'statics',
        'key_word',
        'slug',
        'status',
        'is_homepage',
        'is_in_header',
        'is_in_footer',
        'has_form',
        'meta_description',
        'meta_keywords',
        'published_at',
        'order',
    ];

    protected $casts = [
        'meta_data' => 'array',
        'contents' => 'array',
        'statics' => 'array',
        'is_homepage' => 'boolean',
        'is_in_header' => 'boolean',
        'is_in_footer' => 'boolean',
        'has_form' => 'boolean',
        'published_at' => 'datetime',
        'order' => 'integer',
    ];

    public function getTable(): string
    {
        return config('filament-static-pages.table_name', 'cms_pages');
    }

    protected static function booted(): void
    {
        static::saving(function (self $page): void {
            if (! $page->is_homepage) {
                return;
            }

            static::query()
                ->whereKeyNot($page->getKey())
                ->update([
                    'is_homepage' => false,
                ]);
        });
    }
}
