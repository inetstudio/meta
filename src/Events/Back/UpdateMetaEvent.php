<?php

namespace InetStudio\Meta\Events\Back;

use Illuminate\Queue\SerializesModels;
use InetStudio\Meta\Contracts\Events\Back\UpdateMetaEventContract;

/**
 * Class UpdateMetaEvent.
 */
class UpdateMetaEvent implements UpdateMetaEventContract
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
