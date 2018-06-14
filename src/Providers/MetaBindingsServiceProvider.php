<?php

namespace InetStudio\Meta\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class MetaBindingsServiceProvider.
 */
class MetaBindingsServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public $bindings = [
        // Events
        'InetStudio\Meta\Contracts\Events\Back\UpdateMetaEventContract' => 'InetStudio\Meta\Events\Back\UpdateMetaEvent',

        // Listeners
        'InetStudio\Meta\Contracts\Listeners\Back\ClearMetaCacheListenerContract' => 'InetStudio\Meta\Listeners\Back\ClearMetaCacheListener',

        // Models
        'InetStudio\Meta\Contracts\Models\MetaModelContract' => 'InetStudio\Meta\Models\MetaModel',

        // Services
        'InetStudio\Meta\Contracts\Services\Back\MetaServiceContract' => 'InetStudio\Meta\Services\Back\MetaService',
        'InetStudio\Meta\Contracts\Services\Front\MetaServiceContract' => 'InetStudio\Meta\Services\Front\MetaService',
    ];

    /**
     * Получить сервисы от провайдера.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            'InetStudio\Meta\Contracts\Events\Back\UpdateMetaEventContract',
            'InetStudio\Meta\Contracts\Listeners\Back\ClearMetaCacheListenerContract',
            'InetStudio\Meta\Contracts\Models\MetaModelContract',
            'InetStudio\Meta\Contracts\Services\Back\MetaServiceContract',
            'InetStudio\Meta\Contracts\Services\Front\MetaServiceContract',
        ];
    }
}
