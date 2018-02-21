<?php

namespace InetStudio\Meta\Http\Controllers\Back\Traits;

use InetStudio\Meta\Events\Back\UpdateMetaEvent;

trait MetaManipulationsTrait
{
    /**
     * Сохраняем мета теги.
     *
     * @param $item
     * @param $request
     */
    private function saveMeta($item, $request): void
    {
        if ($request->filled('meta')) {
            foreach ($request->get('meta') as $key => $value) {
                $item->updateMeta($key, $value);
            }

            event(new UpdateMetaEvent($item));
        }
    }
}
