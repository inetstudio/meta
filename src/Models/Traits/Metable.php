<?php

namespace InetStudio\Meta\Models\Traits;

use InetStudio\Meta\Contracts\Models\MetaModelContract;
use InetStudio\Meta\Contracts\Services\Back\MetaServiceContract as BackMetaServiceContract;

trait Metable
{
    /**
     * Получаем все мета теги материала.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function meta()
    {
        return $this->morphMany(app(MetaModelContract::class), 'metable');
    }

    /**
     * Получаем все мета теги.
     *
     * @return mixed
     */
    public function getAllMeta()
    {
        return app(BackMetaServiceContract::class)->getAllMeta($this);
    }

    /**
     * Получаем мета тег.
     *
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function getMeta($key, $default = null)
    {
        return app(BackMetaServiceContract::class)->getMeta($this, $key, $default);
    }

    /**
     * Обновляем мета тег.
     *
     * @param string $key
     * @param $newValue
     * @return mixed
     */
    public function updateMeta($key, $newValue)
    {
        return app(BackMetaServiceContract::class)->updateMeta($this, $key, $newValue);

    }

    /**
     * Добавляем мета тег.
     *
     * @param string $key
     * @param $value
     * @return mixed
     */
    public function addMeta($key, $value)
    {
        return app(BackMetaServiceContract::class)->addMeta($this, $key, $value);

    }

    /**
     * Удаляем мета тег.
     *
     * @param string $key
     * @return mixed
     */
    public function deleteMeta($key)
    {
        return app(BackMetaServiceContract::class)->deleteMeta($this, $key);
    }

    /**
     * Удаляем все мета теги.
     *
     * @return mixed
     */
    public function deleteAllMeta()
    {
        return app(BackMetaServiceContract::class)->deleteAllMeta($this);
    }
}
