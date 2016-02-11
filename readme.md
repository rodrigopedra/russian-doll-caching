# Russian Doll Caching for Laravel 5.2

Inspired by the excellent series at https://laracasts.com/series/russian-doll-caching-in-laravel .

See that series to understand the concepts around it and to use it properly, 
as such how to invalidate the cache upwards.

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
    
    RodrigoPedra\RussianDollCaching\RussianDollCachingServiceProvider::class,
]
```

## Usage

Use the `@russian` directive in your blade templates the same way you would use the `@include` directive.

You can optionally inform a custom key that will be cached added to the cache key.

```php
    @russian('path.to.other.view', compact('user', 'articles'), 'my-custom-key')

    @russian('path.to.view', compact('user', 'articles'))
```

***IMPORTANT*** if key is not informed and the first element in the `data` array is not an Eloquent model, 
then the view will be cached only by its name which can lead to unexpected behavior.

## FAQ

- *Why name this directive `@russian` and not `@cache`?

As a directive named `@cache` can be added to the official Laravel in a future release, this choice aims to
avoid any conflicts.

### License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
