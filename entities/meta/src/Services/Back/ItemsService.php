<?php

namespace InetStudio\MetaPackage\Meta\Services\Back;

use Illuminate\Http\Request;
use InetStudio\AdminPanel\Base\Services\BaseService;
use InetStudio\MetaPackage\Meta\Contracts\Models\MetaModelContract;
use InetStudio\MetaPackage\Meta\Contracts\Services\Back\ItemsServiceContract;

/**
 * Class ItemsService.
 */
class ItemsService extends BaseService implements ItemsServiceContract
{
    /**
     * ItemsService constructor.
     *
     * @param  MetaModelContract  $model
     */
    public function __construct(MetaModelContract $model)
    {
        parent::__construct($model);
    }

    /**
     * Присваиваем мета-теги объекту.
     *
     * @param $meta
     * @param $item
     */
    public function attachToObject($meta, $item): void
    {
        if ($meta instanceof Request) {
            $meta = $meta->get('meta', []);
        } else {
            $meta = (array) $meta;
        }

        if (! empty($meta)) {
            $item->syncMeta($meta);
        } else {
            $item->detachMeta($item->getMetaList());
        }
    }
}
