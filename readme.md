# Russian Doll Caching for Laravel 5.2

Inspired by the excellent series at https://laracasts.com/series/russian-doll-caching-in-laravel

See that series to understand the concepts and use it properly.

## Installation

```
composer require rodrigopedra/russian-doll-caching
```

## Configuration

Add the provider to your config/app.php:

```php
// in your config/app.php add the provider to the service providers key

'providers' => [
    /* ... */
    
    'RodrigoPedra\RussianDollCaching\RussianDollCachingServiceProvider',
]
```

## Usage

Use the `@russian` blade directive in your blade templates substituting the `@include`.

```php
    @russian('path.to.view', compact('user', 'articles'))
```

***IMPORTANT*** the first parameter passed in the `data` array must be an instance of `\Illuminate\Database\Eloquent\Model`

### License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
