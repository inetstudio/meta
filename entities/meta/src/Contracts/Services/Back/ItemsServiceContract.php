<?php

namespace InetStudio\MetaPackage\Meta\Contracts\Services\Back;

use InetStudio\AdminPanel\Base\Contracts\Services\BaseServiceContract;

/**
 * Interface ItemsServiceContract.
 */
interface ItemsServiceContract extends BaseServiceContract
{
    /**
     * Присваиваем мета объекту.
     *
     * @param $meta
     * @param $item
     */
    public function attachToObject($meta, $item): void;
}
