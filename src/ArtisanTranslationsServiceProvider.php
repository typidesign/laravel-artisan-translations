<?php

namespace Typidesign\Translations;

use Illuminate\Support\ServiceProvider;
use Typidesign\Translations\Console\Commands\AddTranslations;

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
        ]);
    }
}
