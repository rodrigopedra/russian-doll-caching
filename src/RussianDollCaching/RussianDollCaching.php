<?php

namespace RodrigoPedra\RussianDollCaching;

use Carbon\Carbon;
use InvalidArgumentException;
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

    public function get( $view, array $data, $key = false )
    {
        $model = reset( $data );

        if ($key === false) {
            if (!$model instanceof Model) {
                throw new InvalidArgumentException( 'First item in data array must be an Eloquent model' );
            }

            // would not cache until updated_at is not null
            $timestamp = $model->updated_at ?: Carbon::now();

            $key = join( '/', [ md5( $view ), get_class( $model ), $model->getKey(), $timestamp->timestamp ] );
        } else {
            $key = join( '/', [ md5( $view ), $key ] );
        }

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
