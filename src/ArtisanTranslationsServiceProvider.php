<?php

declare(strict_types=1);

namespace Typidesign\Translations;

use Illuminate\Support\ServiceProvider;
use Typidesign\Translations\Console\Commands\AddTranslations;
use Typidesign\Translations\Console\Commands\RemoveTranslations;

class ArtisanTranslationsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void {}

    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->commands([
            AddTranslations::class,
            RemoveTranslations::class,
        ]);
    }
}
