<?php

namespace RodrigoPedra\RussianDollCaching;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\View\Factory as View;

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
     * @param  mixed|null $prefixes
     *
     * @return string
     */
    public function get( $view, $data = null, $prefixes = null )
    {
        $data = array_wrap( $data );

        if (!$this->shouldCache) {
            return $this->view->make( $view, $data )->render();
        }

        $prefixes = array_wrap( $prefixes );

        $key = $this->normalizeCacheKey( $view, $data, $prefixes );

        return $this->getCache()->rememberForever( $key, function () use ( $view, $data ) {
            return $this->view->make( $view, $data )->render();
        } );
    }

    /**
     * Makes the cache's key from the data
     *
     * @param  string $view
     * @param  array  $data
     * @param  array  $prefixes
     *
     * @return string
     */
    protected function normalizeCacheKey( $view, array $data, array $prefixes )
    {
        $item = reset( $data );

        if (is_object( $item ) && method_exists( $item, 'getCacheKey' )) {
            array_push( $prefixes, $item->getCacheKey() );
        } elseif (is_object( $item ) && method_exists( $item, '__toString' )) {
            array_push( $prefixes, md5( $item ) );
        }

        array_push( $prefixes, md5( $view ) );

        return join( '/', $prefixes );
    }

    /**
     * Returns the current cache instance
     *
     * @return Cache
     */
    protected function getCache()
    {
        if (method_exists( $this->cache, 'tags' )) {
            return $this->cache->tags( 'russian' );
        }

        return $this->cache;
    }
}
