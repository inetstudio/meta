<?php

namespace InetStudio\Meta\Contracts\Models\Traits;

/**
 * Interface MetableContract.
 */
interface MetableContract
{
    /**
     * Получаем все мета теги материала.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function meta();

    /**
     * Получаем все мета теги.
     *
     * @return mixed
     */
    public function getAllMeta();

    /**
     * Получаем мета тег.
     *
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function getMeta($key, $default = null);

    /**
     * Обновляем мета тег.
     *
     * @param string $key
     * @param $newValue
     * @return mixed
     */
    public function updateMeta($key, $newValue);

    /**
     * Добавляем мета тег.
     *
     * @param string $key
     * @param $value
     * @return mixed
     */
    public function addMeta($key, $value);

    /**
     * Удаляем мета тег.
     *
     * @param string $key
     * @return mixed
     */
    public function deleteMeta($key);

    /**
     * Удаляем все мета теги.
     *
     * @return mixed
     */
    public function deleteAllMeta();
}
