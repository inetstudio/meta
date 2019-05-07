<?php

namespace InetStudio\MetaPackage\Meta\Providers;

use Collective\Html\FormBuilder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class ServiceProvider.
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Загрузка сервиса.
     */
    public function boot(): void
    {
        $this->registerConsoleCommands();
        $this->registerPublishes();
        $this->registerViews();
        $this->registerFormComponents();
    }

    /**
     * Регистрация команд.
     */
    protected function registerConsoleCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands(
            [
                'InetStudio\MetaPackage\Meta\Console\Commands\SetupCommand',
            ]
        );
    }

    /**
     * Регистрация ресурсов.
     */
    protected function registerPublishes(): void
    {
        $this->publishes(
            [
                __DIR__.'/../../config/meta.php' => config_path('meta.php'),
            ], 'config'
        );

        $this->mergeConfigFrom(
            __DIR__.'/../../config/services.php', 'services'
        );

        if (! $this->app->runningInConsole()) {
            return;
        }

        if (Schema::hasTable('meta')) {
            return;
        }

        $timestamp = date('Y_m_d_His', time());
        $this->publishes(
            [
                __DIR__.'/../../database/migrations/create_meta_tables.php.stub' => database_path(
                    'migrations/'.$timestamp.'_create_meta_tables.php'
                ),
            ], 'migrations'
        );
    }

    /**
     * Регистрация представлений.
     */
    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'admin.module.meta');
    }

    /**
     * Регистрация компонентов форм.
     */
    protected function registerFormComponents()
    {
        FormBuilder::component(
            'meta', 'admin.module.meta::back.forms.groups.meta', ['name' => null, 'value' => null, 'attributes' => null]
        );
        FormBuilder::component(
            'social_meta', 'admin.module.meta::back.forms.groups.social_meta',
            ['name' => null, 'value' => null, 'attributes' => null]
        );
    }
}
