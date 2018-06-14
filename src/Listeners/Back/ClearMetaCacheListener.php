<?php

namespace InetStudio\Meta\Listeners\Back;

use Illuminate\Support\Facades\Cache;
use InetStudio\Meta\Contracts\Events\Back\UpdateMetaEventContract;
use InetStudio\Meta\Contracts\Listeners\Back\ClearMetaCacheListenerContract;

/**
 * Class ClearMetaCacheListener.
 */
class ClearMetaCacheListener implements ClearMetaCacheListenerContract
{
    /**
     * Handle the event.
     *
     * @param UpdateMetaEventContract $event
     *
     * @return void
     */
    public function handle(UpdateMetaEventContract $event)
    {
        $object = $event->object;

        $cacheKey = md5(get_class($object).$object->id);

        Cache::forget('MetaService_getAllTags_'.$cacheKey);
        Cache::forget('MetaService_getTitle_'.$cacheKey);
        Cache::forget('MetaService_getDescription_'.$cacheKey);
        Cache::forget('MetaService_getRobots_'.$cacheKey);
        Cache::forget('MetaService_getKeywords_'.$cacheKey);
        Cache::forget('MetaService_getOpenGraph_'.$cacheKey);
        Cache::forget('MetaService_getCanonical_'.$cacheKey);
    }
}
