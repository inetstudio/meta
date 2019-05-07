<?php

namespace InetStudio\MetaPackage\Meta\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class BindingsServiceProvider.
 */
class BindingsServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    /**
     * @var array
     */
    public $bindings = [
        'InetStudio\MetaPackage\Meta\Contracts\Models\MetaModelContract' => 'InetStudio\MetaPackage\Meta\Models\MetaModel',
        'InetStudio\MetaPackage\Meta\Contracts\Services\Back\ItemsServiceContract' => 'InetStudio\MetaPackage\Meta\Services\Back\ItemsService',
        'InetStudio\MetaPackage\Meta\Contracts\Services\Front\ItemsServiceContract' => 'InetStudio\MetaPackage\Meta\Services\Front\ItemsService',
    ];

    /**
     * Получить сервисы от провайдера.
     *
     * @return array
     */
    public function provides()
    {
        return array_keys($this->bindings);
    }
}
