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

    /** @var bool */
    protected $shouldCache;

    /**
     * RussianDollCaching constructor.
     *
     * @param  \Illuminate\Contracts\Cache\Repository $cache
     * @param  \Illuminate\Contracts\View\Factory     $view
     * @param  bool                                   $shouldCache
     */
    public function __construct( Cache $cache, View $view, $shouldCache = true )
    {
        $this->cache       = $cache;
        $this->view        = $view;
        $this->shouldCache = $shouldCache;
    }

    /**
     * Returns the cached view
     *
     * @param  string     $view
     * @param  mixed|null $data
     * @param  mixed|null $prefix
     * @param  mixed|null $tags
     *
     * @return string
     */
    public function get( $view, $data = null, $prefix = null, $tags = null )
    {
        $key = $this->makeKey( $view, (array)$data, (array)$prefix );

        if (!$this->shouldCache) {
            return $this->view->make( $view, $data )->render();
        }

        return $this->getCache( (array)$tags )->rememberForever( $key, function () use ( $view, $data ) {
            return $this->view->make( $view, $data )->render();
        } );
    }

    /**
     * Makes the cache's key from the data
     *
     * @param  string $view
     * @param  array  $data
     * @param  array  $prefix
     *
     * @return string
     */
    protected function makeKey( $view, array $data, array $prefix )
    {
        $parts = array_merge( $prefix, [ md5( $view ) ] );

        $model = reset( $data );

        if ($model instanceof Model) {
            // will generate a new cache until $model->updated_at is not null
            $timestamp = $model->updated_at ?: Carbon::now();

            // use the + array union operator
            // @see http://php.net/manual/en/function.array-merge.php
            $parts = array_merge( $parts, [ get_class( $model ), $model->getKey(), $timestamp->timestamp ] );
        }

        return join( '/', $parts );
    }

    /**
     * Returns the current cache instance
     *
     * @param  array $tags
     *
     * @return Cache
     */
    protected function getCache( array $tags )
    {
        if ($this->cache instanceof TaggableStore) {
            return $this->cache->tags( array_merge( [ 'russian' ], $tags ) );
        }

        return $this->cache;
    }
}
