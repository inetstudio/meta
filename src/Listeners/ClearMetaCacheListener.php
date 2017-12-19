<?php

namespace InetStudio\Meta\Listeners;

use Illuminate\Support\Facades\Cache;
use InetStudio\Meta\Events\UpdateMetaEvent;

class ClearMetaCacheListener
{
    /**
     * ClearMetaCacheListener constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param UpdateMetaEvent $event
     * @return void
     */
    public function handle(UpdateMetaEvent $event)
    {
        $object = $event->object;

        $cacheKey = md5(get_class($object).$object->id);

        Cache::forget('MetaService_getAllTags_'.$cacheKey);
        Cache::forget('MetaService_getTitle_'.$cacheKey);
        Cache::forget('MetaService_getDescription_'.$cacheKey);
        Cache::forget('MetaService_getRobots_'.$cacheKey);
        Cache::forget('MetaService_getKeywords_'.$cacheKey);
        Cache::forget('MetaService_getOpenGraph_'.$cacheKey);
    }
}
