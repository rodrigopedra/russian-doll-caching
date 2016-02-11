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

    /**
     * RussianDollCaching constructor.
     *
     * @param  \Illuminate\Contracts\Cache\Repository $cache
     * @param  \Illuminate\Contracts\View\Factory     $view
     */
    public function __construct( Cache $cache, View $view )
    {
        $this->cache = $cache;
        $this->view  = $view;
    }

    /**
     * Returns the cached view
     *
     * @param  string      $view
     * @param  mixed       $data
     * @param  string|null $prefix
     *
     * @return string
     */
    public function get( $view, $data, $prefix = null )
    {
        $key = $this->makeKey( $view, (array)$data, $prefix );

        return $this->getCache()->rememberForever( $key, function () use ( $view, $data ) {
            return $this->view->make( $view, $data )->render();
        } );
    }

    /**
     * Makes the cache's key from the data
     *
     * @param  string      $view
     * @param  array       $data
     * @param  string|null $prefix
     *
     * @return string
     */
    protected function makeKey( $view, array $data, $prefix )
    {
        $parts = [ ];

        if (!empty( $prefix )) {
            $parts[] = $prefix;
        }

        $parts[] = md5( $view );

        $model = reset( $data );

        if ($model instanceof Model) {
            // will generate a new cache until $model->updated_at is not null
            $timestamp = $model->updated_at ?: Carbon::now();

            // use the + array union operator
            // @see http://php.net/manual/en/function.array-merge.php
            $parts = $parts + [ get_class( $model ), $model->getKey(), $timestamp->timestamp ];
        }

        return join( '/', $parts );
    }

    /**
     * Returns the current cache instance
     *
     * @return  \Illuminate\Contracts\Cache\Repository
     */
    protected function getCache()
    {
        if ($this->cache instanceof TaggableStore) {
            return $this->cache->tags( 'russian' );
        }

        return $this->cache;
    }
}
