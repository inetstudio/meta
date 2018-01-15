<?php

namespace InetStudio\Meta\Providers;

use Collective\Html\FormBuilder;
use Illuminate\Support\ServiceProvider;

class FormBuilderServiceProvider extends ServiceProvider
{
    /**
     * Загрузка сервиса.
     *
     * @return void
     */
    public function boot(): void
    {
        FormBuilder::component('meta', 'admin.module.meta::back.forms.groups.meta', ['name' => null, 'value' => null, 'attributes' => null]);
        FormBuilder::component('social_meta', 'admin.module.meta::back.forms.groups.social_meta', ['name' => null, 'value' => null, 'attributes' => null]);
    }

    /**
     * Регистрация привязки в контейнере.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }
}
