<?php

namespace InetStudio\Meta\Contracts\Models;

/**
 * Interface MetaModelContract
 * @package InetStudio\Meta\Contracts\Models
 */
interface MetaModelContract
{
    /**
     * Полиморфное отношение с остальными моделями.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function metable();
}
