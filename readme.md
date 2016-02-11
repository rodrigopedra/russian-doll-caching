# Russian Doll Caching for Laravel 5.1 LTS and Laravel 5.2

Efficient view caching.

Inspired by the excellent series at https://laracasts.com/series/russian-doll-caching-in-laravel .

Watch that series to understand the concepts around it and to use it properly, 
for example: how to invalidate the cache upwards.

## Installation

In your terminal/shell run:

```
composer require rodrigopedra/russian-doll-caching
```

Add the provider to your config/app.php service providers array:

```php
/* ... */

'providers' => [
    /* ... */
    
    RodrigoPedra\RussianDollCaching\RussianDollCachingServiceProvider::class,
],

/* ... */
```

## Configuration

In your terminal/shell run:

```
php artisan vendor:publish --provider="RodrigoPedra\RussianDollCaching\RussianDollCachingServiceProvider"
```

You can configure a `should_cache` constraint, so you can skip caching, for example, while developing locally.

## Usage

Use the `@russian` directive in your blade templates the same way you would use the `@include` directive.

You can optionally inform a custom prefix that will be prepended to the cache key.

```php
    @russian('path.to.other.view', compact('user', 'articles'), 'version-prefix')

    @russian('path.to.view', compact('user', 'articles'))
```

***IMPORTANT*** if no prefix is informed and the first element in the `data` array is not an Eloquent model, 
then the view will be cached only by its name hash which can lead to unexpected behavior.

## FAQ

- Why name this directive `@russian` and not `@cache`?

  As a directive named `@cache` can be added to the official Laravel in a future release, this choice aims 
  to avoid any conflicts.

- Why are my views not updating?
  
  Try running `php artisan view:clear` and `php artisan cache:clear`. Also, while developing, set the `should_cache` 
  config key to `false`.

- Can I flush just the items cached by this package?
  
  If you use a caching mechanism that supports tagging (`memcached` or `redis`) all the cached items are created with 
  a `russian` tag. So you can clear only these items running `\Cache::tags('russian')->flush()` in `php artisan tinker`


### License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
