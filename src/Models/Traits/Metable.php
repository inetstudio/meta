<?php

namespace InetStudio\Meta\Models\Traits;

/**
 * Trait Metable.
 */
trait Metable
{
    /**
     * Получаем все мета теги материала.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function meta()
    {
        return $this->morphMany(app()->make('InetStudio\Meta\Contracts\Models\MetaModelContract'), 'metable');
    }

    /**
     * Получаем все мета теги.
     *
     * @return mixed
     */
    public function getAllMeta()
    {
        return app()->make('InetStudio\Meta\Contracts\Services\Back\MetaServiceContract')->getAllMeta($this);
    }

    /**
     * Получаем мета тег.
     *
     * @param string $key
     * @param null $default
     *
     * @return mixed
     */
    public function getMeta($key, $default = null)
    {
        return app()->make('InetStudio\Meta\Contracts\Services\Back\MetaServiceContract')->getMeta($this, $key, $default);
    }

    /**
     * Обновляем мета тег.
     *
     * @param string $key
     * @param $newValue
     *
     * @return mixed
     */
    public function updateMeta($key, $newValue)
    {
        return app()->make('InetStudio\Meta\Contracts\Services\Back\MetaServiceContract')->updateMeta($this, $key, $newValue);
    }

    /**
     * Добавляем мета тег.
     *
     * @param string $key
     * @param $value
     *
     * @return mixed
     */
    public function addMeta($key, $value)
    {
        return app()->make('InetStudio\Meta\Contracts\Services\Back\MetaServiceContract')->addMeta($this, $key, $value);
    }

    /**
     * Удаляем мета тег.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function deleteMeta($key)
    {
        return app()->make('InetStudio\Meta\Contracts\Services\Back\MetaServiceContract')->deleteMeta($this, $key);
    }

    /**
     * Удаляем все мета теги.
     *
     * @return mixed
     */
    public function deleteAllMeta()
    {
        return app()->make('InetStudio\Meta\Contracts\Services\Back\MetaServiceContract')->deleteAllMeta($this);
    }
}
