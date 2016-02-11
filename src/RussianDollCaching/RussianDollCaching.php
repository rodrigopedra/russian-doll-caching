<?php

namespace RodrigoPedra\RussianDollCaching;

use Carbon\Carbon;
use Illuminate\Cache\TaggableStore;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\View\Factory as View;
use Illuminate\Contracts\Cache\Repository as Cache;

class RussianDollCaching
{
    /** @var Cache */
    protected $cache;

    /** @var View */
    protected $view;

    public function __construct( Cache $cache, View $view )
    {
        $this->cache = $cache;
        $this->view  = $view;
    }

    public function get( $view, array $data, $key = null )
    {
        $parts = array_filter( [ $key, md5( $view ) ] );

        $model = reset( $data );

        if ($model instanceof Model) {
            // will generate a new cache until updated_at is not null
            $timestamp = $model->updated_at ?: Carbon::now();

            $parts = array_merge( $parts, [ get_class( $model ), $model->getKey(), $timestamp->timestamp ] );
        }

        $key = join( '/', $parts );

        if ($this->cache instanceof TaggableStore) {
            return $this->cache->tags( 'russian' )->rememberForever( $key, function () use ( $view, $data ) {
                return $this->view->make( $view, $data )->render();
            } );
        }

        return $this->cache->rememberForever( $key, function () use ( $view, $data ) {
            return $this->view->make( $view, $data )->render();
        } );
    }
}
