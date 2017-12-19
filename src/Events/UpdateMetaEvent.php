<?php

namespace InetStudio\Meta\Events;

use Illuminate\Queue\SerializesModels;

class UpdateMetaEvent
{
    use SerializesModels;

    public $object;

    /**
     * Create a new event instance.
     *
     * UpdateMetaEvent constructor.
     * @param $object
     */
    public function __construct($object)
    {
        $this->object = $object;
    }
}
