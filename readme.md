# Russian Doll Caching for Laravel 5.3, 5.4, 5.5 and 5.6

Efficient view caching.

Inspired by the excellent series at https://laracasts.com/series/russian-doll-caching-in-laravel .

Watch that series to understand the concepts around it and to use it properly, 
for example: how to invalidate the cache upwards.

## Installation

In your terminal/shell run:

```
composer require rodrigopedra/russian-doll-caching
```

If you're using Laravel 5.3 or 5.4 you need to register the service provider in 
your `config/app.php` providers array:

```php
// ... 

'providers' => [
    // ... 
    
    RodrigoPedra\RussianDollCaching\RussianDollCachingServiceProvider::class,
],

// ... 
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
    @russian('path.to.view', compact('user', 'articles'), 'version-prefix')

    @russian('path.to.other.view', compact('user', 'articles'))
    
    {{-- caution here, the cache key will only be the view name hash! --}}
    @russian('path.to.another.view')
```

You can also pass multiple prefixes as an `array`, that will be prepended to the cache item's key:

```php
    @russian('path.to.other.view', compact('user'), [ 'v1', 'home' ])
```

If your cache mechanism supports tagging, like `memcached` or `redis`, all cache items will be 
cached with a `russian` tag. 


### Key calculation

The caching mechanism will try to use the first element in the `data` array as part of the cache key.

You should use the `RussianCacheableModel` trait in your models or use the `RussianCacheableCollection` in 
your custom collections. This traits add a `getCacheKey` method to this objects so you can 


## FAQ

- Why are my views not updating?
  
  Try running `php artisan view:clear` and `php artisan cache:clear`. Also, while developing, set the `should_cache` 
  config key to `false`.

- Can I flush just the items cached by this package?
  
  If you use a caching mechanism that supports tagging (`memcached` or `redis`) all the cached items are created with 
  a `russian` tag. So you can clear only these items running `\Cache::tags('russian')->flush()` in `php artisan tinker`


### License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
