# Create notes on eloquent models. Supports multiple note types as well as multi-tenancy

[![Latest Version on Packagist](https://img.shields.io/packagist/v/enginedigital/model-notes.svg?style=flat-square)](https://packagist.org/packages/enginedigital/model-notes)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/enginedigital/model-notes/run-tests?label=tests)](https://github.com/enginedigital/model-notes/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/enginedigital/model-notes/Check%20&%20fix%20styling?label=code%20style)](https://github.com/enginedigital/model-notes/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/enginedigital/model-notes.svg?style=flat-square)](https://packagist.org/packages/enginedigital/model-notes)

> Create notes on eloquent models. Supports multiple note types as well as multi-tenancy.

## Installation

You can install the package via composer:

```bash
composer require enginedigital/model-notes
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="EngineDigital\Note\NoteServiceProvider" --tag="model-notes-migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="EngineDigital\Note\NoteServiceProvider" --tag="model-notes-config"
```

This is the contents of the published config file:

```php
return [
    'note_model' => EngineDigital\Note\Note::class,
    'note_default_type' => 'plain',
    'note_types' => [
        'plain',
        'html',
        'markdown',
        'json',
    ],
    'model_primary_key_attribute' => 'model_id',
    'tenant_model' => null, // App\Models\Company::class
    'tenant_resolver' => null, // a class that uses `__invoke` to get the id of the current tenant
];
```

## Usage

```php
// User model uses EngineDigital\Note\HasNotes
$user->setNote('This is my cute little note!');
```

## Testing

```bash
composer run test
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Credits

- [James Doyle](https://github.com/james2doyle)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
