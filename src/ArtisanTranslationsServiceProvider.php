<?php

namespace Typidesign\Translations;

use Illuminate\Support\ServiceProvider;
use Typidesign\Translations\Console\Commands\AddTranslations;
use Typidesign\Translations\Console\Commands\RemoveTranslations;

class ArtisanTranslationsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->commands([
            AddTranslations::class,
            RemoveTranslations::class,
        ]);
    }
}
