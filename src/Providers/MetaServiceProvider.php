<?php

namespace InetStudio\Meta\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

/**
 * Class MetaServiceProvider.
 */
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
     * Регистрация команд.
     *
     * @return void
     */
    protected function registerConsoleCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                'InetStudio\Meta\Console\Commands\SetupCommand',
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

        $this->mergeConfigFrom(
            __DIR__.'/../../config/services.php', 'services'
        );

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
        Event::listen(
            'InetStudio\Meta\Contracts\Events\Back\UpdateMetaEventContract',
            'InetStudio\Meta\Contracts\Listeners\Back\ClearMetaCacheListenerContract'
        );
    }
}
