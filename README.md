# Manage translations in json files.

This package gives you an artisan command to manage translations in Laravel 5.4+ json files.

## Installation

You can install the package via composer:

```bash
composer require typidesign/laravel-artisan-translations
```

Now add the service provider in `config/app.php` file:

```php
'providers' => [
    // ...
    Typidesign\Translations\ArtisanTranslationsServiceProvider::class,
];
```

## Usage

### Add translations from a single file

```php
php artisan translations:add vendor/typicms/pages/src/resources/lang/fr.json
```

Every translations present in this file will be added to ```/resources/lang/fr.json```.

### Add translations from a directory

```php
php artisan translations:add vendor/typicms/pages/src/resources/lang
```

Every translations found in this directory will be added to ```/resources/lang```

### Overwrite translations

By default, translation keys will not be overwritten. You can use the ```--force``` option to overwrite existing keys:

### Remove translations

```php
php artisan translations:remove vendor/typicms/pages/src/resources/lang[/lg.json]
```

Every translations found in this file/directory will be removed from ```/resources/lang```


```php
php artisan translations:add vendor/typicms/pages/src/resources/lang --force
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email samuel@typidesign.be instead of using the issue tracker.

## Credits

- [Samuel De Backer](https://github.com/sdebacker)
- [All Contributors](../../contributors)

## About Typi Design

Typi Design is a webdesign agency based in Brussels, Belgium.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
