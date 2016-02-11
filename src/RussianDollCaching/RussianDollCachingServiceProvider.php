<?php

namespace RodrigoPedra\RussianDollCaching;

use Illuminate\Support\ServiceProvider;

class RussianDollCachingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app[ 'blade.compiler' ]->directive( 'russian', function ( $expression ) {
            $className = RussianDollCaching::class;

            return "<?php echo app('{$className}')->get{$expression}; ?>";
        } );
    }

    public function register()
    {
        $this->app->singleton( RussianDollCaching::class, function ( $app ) {
            return new RussianDollCaching( $app[ 'cache.store' ], $app[ 'view' ] );
        } );
    }
}
