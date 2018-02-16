<?php

namespace RodrigoPedra\RussianDollCaching\Traits;

trait RussianCacheableCollection
{
    /**
     * Calculate a unique cache key for the model instance.
     */
    public function getCacheKey()
    {
        return sprintf( "%s/%s",
            get_class( $this ),
            md5( $this->toJson() )
        );
    }
}
