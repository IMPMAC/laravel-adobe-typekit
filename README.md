# Manage self-hosted Adobe Typekit Fonts in Laravel apps

[![Latest Version on Packagist](https://img.shields.io/packagist/v/weble/laravel-adobe-typekit.svg?style=flat-square)](https://packagist.org/packages/weble/laravel-adobe-typekit)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/weble/laravel-adobe-typekit/run-tests?label=tests)](https://github.com/weble/laravel-adobe-typekit/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/weble/laravel-adobe-typekit/Check%20&%20fix%20styling?label=code%20style)](https://github.com/weble/laravel-adobe-typekit/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/weble/laravel-adobe-typekit.svg?style=flat-square)](https://packagist.org/packages/weble/laravel-adobe-typekit)

This package makes self-hosting Adobe Typekit Fonts as frictionless as possible for Laravel users.  To load fonts in your application, register a Adobe Typekit Fonts embed URL and load it with the `@typekit` Blade directive.

It's not really within the Typekit policy, but their speed is so bad that this is required.

Cloned from the [Spatie Google Fonts Package](https://github.com/weble/laravel-adobe-typekit)

```php
// config/adobe.typekit.php

return [
    'fonts' => [
        'default' => 'https://use.typekit.net/[project-id].css',
    ],
];
```

```blade
{{-- resources/views/layouts/app.blade.php --}}

<head>
    {{-- Loads default --}}
    @typekit

    {{-- Loads code project --}}
    @typekit('code')
</head>
```

When fonts are requested the first time, this package will scrape the CSS, fetch the assets from Adobe's servers, store them locally, and render the CSS inline.

If anything goes wrong in this process, the package falls back to a `<link>` tag to load the fonts from Adobe.

## Installation

You can install the package via composer:

```bash
composer require weble/laravel-adobe-typekit
```

You may optionally publish the config file:

```bash
php artisan vendor:publish --provider="Weble\AdobeTypekit\AdobeTypekitServiceProvider" --tag="adobe-typekit-config"
```

Here's what the config file looks like:

```php
return [

    /*
     * Here you can register fonts to call from the @tyepkit Blade directive.
     * The typekit:fetch command will prefetch these fonts.
     */
    'fonts' => [
        'default' => 'https://use.typekit.net/[project-id].css',
    ],

    /*
     * This disk will be used to store local Adobe Typekit Fonts. The public disk
     * is the default because it can be served over HTTP with storage:link.
     */
    'disk' => 'public',

    /*
     * Prepend all files that are written to the selected disk with this path.
     * This allows separating the fonts from other data in the public disk.
     */
    'path' => 'fonts',

    /*
     * By default, CSS will be inlined to reduce the amount of round trips
     * browsers need to make in order to load the requested font files.
     */
    'inline' => true,

    /*
     * When something goes wrong fonts are loaded directly from Adobe.
     * With fallback disabled, this package will throw an exception.
     */
    'fallback' => ! env('APP_DEBUG'),

    /*
     * This user agent will be used to request the stylesheet from Adobe Tyepkit.
     * This is the Safari 14 user agent that only targets modern browsers. If
     * you want to target older browsers, use different user agent string.
     */
    'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0.3 Safari/605.1.15',

];
```

## Usage

To add fonts to your application, grab an embed code from Adobe Typekit fonts, register it in the config and use the `@typekit` Blade directive.


```blade
{{-- resources/views/layouts/app.blade.php --}}

<head>
    {{-- Loads Default --}}
    @typekit

    {{-- Loads code project --}}
    @typekit('code')
</head>
```

This will inline the CSS, so the browser needs to do one less round-trip. If you prefer an external CSS file, you may disable the `inline` option in the package configuration.

Fonts are stored in a `fonts` folder on the `public` disk. You'll need to run `php artisan storage:link` to ensure the files can be served over HTTP. If you wish to store fonts in the git repository, make sure `storage/app/public` is not ignored.

If you want to serve fonts from a CDN, you may set up a different disk configuration.

## Prefetching fonts

If you want to make sure fonts are ready to go before anyone visits your site, you can prefetch them with this artisan command.

```bash
php artisan typekit:fetch
```

### Caveats for legacy browsers

Adobe Typekit Fonts' servers sniff the visitor's user agent header to determine which font format to serve. This means fonts work in all modern and legacy browsers.

This package isn't able to tailor to different user agents. With the default configuration, only browsers that can handle WOFF 2.0 font files are supported. At the time of writing, this is >95% of all users according to [caniuse](https://caniuse.com/woff2). Most notably, IE doesn't support WOFF 2.0.

If you need to serve fonts to a legacy browser, you may specify a different user agent string in the configuration. Keep in mind that makes the page load heavier for all visitors, including modern browsers.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Sebastian De Deyne](https://github.com/sebastiandedeyne)
- [Freek Van der Herten](https://github.com/freekmurze)
- [Daniele Rosario](https://github.com/skullbock)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
