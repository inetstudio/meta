<?php

namespace InetStudio\Meta\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class MetaBindingsServiceProvider.
 */
class MetaBindingsServiceProvider extends ServiceProvider
{
    /**
    * @var  bool
    */
    protected $defer = true;

    /**
    * @var  array
    */
    public $bindings = [
        'InetStudio\Meta\Contracts\Models\MetaModelContract' => 'InetStudio\Meta\Models\MetaModel',
        'InetStudio\Meta\Contracts\Models\Traits\MetableContract' => 'InetStudio\Meta\Models\Traits\Metable',
        'InetStudio\Meta\Contracts\Services\Back\MetaServiceContract' => 'InetStudio\Meta\Services\Back\MetaService',
        'InetStudio\Meta\Contracts\Services\Front\MetaServiceContract' => 'InetStudio\Meta\Services\Front\MetaService',
    ];

    /**
     * Получить сервисы от провайдера.
     *
     * @return  array
     */
    public function provides()
    {
        return array_keys($this->bindings);
    }
}
