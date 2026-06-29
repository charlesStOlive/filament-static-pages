<?php

use Illuminate\Support\Arr;

if (! function_exists('filament_static_pages_settings')) {
    /**
     * Get configured settings instance.
     */
    function filament_static_pages_settings(): ?object
    {
        $settingsClass = config('filament-static-pages.settings.class');

        if (! $settingsClass || ! class_exists($settingsClass)) {
            return null;
        }

        return app($settingsClass);
    }
}

if (! function_exists('filament_static_pages_setting')) {
    /**
     * Get a specific setting value with support for dot notation.
     */
    function filament_static_pages_setting(string $key, mixed $default = null): mixed
    {
        $settings = filament_static_pages_settings();

        if (! $settings) {
            return $default;
        }

        $data = method_exists($settings, 'toArray')
            ? $settings->toArray()
            : $settings;

        return data_get($data, $key, $default);
    }
}
