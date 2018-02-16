<?php

namespace RodrigoPedra\RussianDollCaching;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class RussianDollCachingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes( [ __DIR__ . DIRECTORY_SEPARATOR . 'config.php' => config_path( 'russian.php' ) ] );

        $className = RussianDollCaching::class;

        Blade::directive( 'russian', function ( $expression ) use ( $className ) {
            return "<?php echo app('{$className}')->get({$expression}); ?>";
        } );
    }

    public function register()
    {
        $this->mergeConfigFrom( __DIR__ . DIRECTORY_SEPARATOR . 'config.php', 'russian' );

        $this->app->singleton( RussianDollCaching::class, function ( $app ) {
            return new RussianDollCaching( $app[ 'cache.store' ], $app[ 'view' ], !!config( 'russian.should_cache' ) );
        } );
    }
}
