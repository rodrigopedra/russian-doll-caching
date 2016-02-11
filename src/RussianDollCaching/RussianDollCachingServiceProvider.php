<?php

namespace RodrigoPedra\RussianDollCaching;

use Illuminate\Support\ServiceProvider;

class RussianDollCachingServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app[ 'blade.compiler' ]->directive( 'russian', function ( $expression ) {
            return "<?php echo app('\\RodrigoPedra\\RussianDollCaching')->get{$expression}; ?>";
        } );
    }

    public function register()
    {
        $this->app->singleton( RussianDollCaching::class, function ( $app ) {
            return new RussianDollCaching( $app[ 'cache' ], $app[ 'view' ] );
        } );
    }
}
