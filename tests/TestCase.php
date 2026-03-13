<?php

declare(strict_types=1);

namespace Typidesign\Translations\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Typidesign\Translations\ArtisanTranslationsServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            ArtisanTranslationsServiceProvider::class,
        ];
    }
}
