# CRCMS Foundation

[![Latest Stable Version](https://poser.pugx.org/crcms/foundation/v/stable)](https://packagist.org/packages/crcms/foundation)
[![License](https://poser.pugx.org/crcms/foundation/license)](https://packagist.org/packages/crcms/foundation)
[![StyleCI](https://github.styleci.io/repos/157184276/shield?branch=master)](https://github.styleci.io/repos/157184276)

Laravel-based module, interface base package, applied to crcms global

## Install

You can install the package via composer:

```
composer require crcms/foundation
```

## Laravel

If your version is less than 5.5 please modify ``config / app.php``

```
'providers' => [
    CrCms\Foundation\Providers\FoundationServiceProvider::class,
]

```

If you'd like to make configuration changes in the configuration file you can pubish it with the following Aritsan command:
```
php artisan vendor:publish --provider="CrCms\Foundation\Providers\FoundationServiceProvider"
```

## Commands

```
php artisan make:module {module}
```

## License
[MIT license](https://opensource.org/licenses/MIT)
