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

You can optionally inform a custom key that will be cached with the hashed view name.

```php
    @russian('path.to.other.view', compact('user', 'articles'), 'my-custom-key')

    @russian('path.to.view', compact('user', 'articles'))
```

***IMPORTANT*** if key is not informed the first element in the `data` array must be an Eloquent model.

### License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
