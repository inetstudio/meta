<?php

namespace InetStudio\Meta\Contracts\Models;

/**
 * Interface MetaModelContract.
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
