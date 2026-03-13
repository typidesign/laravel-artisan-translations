# Manage translations in json files

[![Latest Version on Packagist](https://img.shields.io/packagist/v/typidesign/laravel-artisan-translations.svg?style=flat-square)](https://packagist.org/packages/typidesign/laravel-artisan-translations)
[![Tests](https://github.com/typidesign/laravel-artisan-translations/actions/workflows/tests.yml/badge.svg)](https://github.com/typidesign/laravel-artisan-translations/actions/workflows/tests.yml)
[![PHPStan](https://github.com/typidesign/laravel-artisan-translations/actions/workflows/phpstan.yml/badge.svg)](https://github.com/typidesign/laravel-artisan-translations/actions/workflows/phpstan.yml)
[![Rector](https://github.com/typidesign/laravel-artisan-translations/actions/workflows/rector.yml/badge.svg)](https://github.com/typidesign/laravel-artisan-translations/actions/workflows/rector.yml)
[![Pint](https://github.com/typidesign/laravel-artisan-translations/actions/workflows/pint.yml/badge.svg)](https://github.com/typidesign/laravel-artisan-translations/actions/workflows/pint.yml)

This package provides artisan commands to manage translations in Laravel JSON files.

## Installation

You can install the package via composer:

```bash
composer require typidesign/laravel-artisan-translations
```

The service provider will be auto-discovered by Laravel.

## Usage

### Add translations from a single file

```bash
php artisan translations:add vendor/typicms/pages/src/lang/fr.json
```

Every translation present in this file will be added to `lang/fr.json`.

### Add translations from a directory

```bash
php artisan translations:add vendor/typicms/pages/src/lang
```

Every translation found in this directory will be added to `lang/`.

### Overwrite translations

By default, existing translation keys will not be overwritten. Use the `--force` option to overwrite them:

```bash
php artisan translations:add vendor/typicms/pages/src/lang --force
```

### Remove translations

Remove translations found in a file or directory from `lang/`:

```bash
php artisan translations:remove vendor/typicms/pages/src/lang/fr.json
php artisan translations:remove vendor/typicms/pages/src/lang
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

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
