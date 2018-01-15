<?php

namespace InetStudio\Meta\Providers;

use Illuminate\Support\Facades\Event;
use InetStudio\Meta\Models\MetaModel;
use Illuminate\Support\ServiceProvider;
use InetStudio\Meta\Events\UpdateMetaEvent;
use InetStudio\Meta\Console\Commands\SetupCommand;
use InetStudio\Meta\Listeners\ClearMetaCacheListener;
use InetStudio\Meta\Contracts\Models\MetaModelContract;
use InetStudio\Meta\Services\Back\MetaService as BackMetaService;
use InetStudio\Meta\Services\Front\MetaService as FrontMetaService;
use InetStudio\Meta\Contracts\Services\Back\MetaServiceContract as BackMetaServiceContract;
use InetStudio\Meta\Contracts\Services\Front\MetaServiceContract as FrontMetaServiceContract;

class MetaServiceProvider extends ServiceProvider
{
    /**
     * Загрузка сервиса.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerConsoleCommands();
        $this->registerPublishes();
        $this->registerViews();
        $this->registerEvents();
    }

    /**
     * Регистрация привязки в контейнере.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerBindings();
    }

    /**
     * Регистрация команд.
     *
     * @return void
     */
    protected function registerConsoleCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SetupCommand::class,
            ]);
        }
    }

    /**
     * Регистрация ресурсов.
     *
     * @return void
     */
    protected function registerPublishes(): void
    {
        $this->publishes([
            __DIR__.'/../../config/meta.php' => config_path('meta.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            if (! class_exists('CreateMetaTables')) {
                $timestamp = date('Y_m_d_His', time());
                $this->publishes([
                    __DIR__.'/../../database/migrations/create_meta_tables.php.stub' => database_path('migrations/'.$timestamp.'_create_meta_tables.php'),
                ], 'migrations');
            }
        }
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'admin.module.meta');
    }

    /**
     * Регистрация событий.
     *
     * @return void
     */
    protected function registerEvents(): void
    {
        Event::listen(UpdateMetaEvent::class, ClearMetaCacheListener::class);
    }

    /**
     * Регистрация привязок, алиасов и сторонних провайдеров сервисов.
     *
     * @return void
     */
    public function registerBindings(): void
    {
        $this->app->bind(MetaModelContract::class, MetaModel::class);
        $this->app->singleton(BackMetaServiceContract::class, BackMetaService::class);
        $this->app->singleton(FrontMetaServiceContract::class, FrontMetaService::class);
    }
}
