<?php

namespace InetStudio\Meta\Contracts\Services\Back;

use InetStudio\Meta\Contracts\Models\Traits\MetableContract;

/**
 * Interface MetaServiceContract
 * @package InetStudio\Meta\Contracts\Services\Back
 */
interface MetaServiceContract
{
    /**
     * Проверяем наличие мета тега.
     *
     * @param MetableContract $metable
     * @param string $key
     * @return mixed
     */
    public function hasMeta(MetableContract $metable, string $key): bool;

    /**
     * Получаем все мета теги.
     *
     * @param MetableContract $metable
     * @return mixed
     */
    public function getAllMeta(MetableContract $metable);

    /**
     * Получаем мета тег.
     *
     * @param MetableContract $metable
     * @param string $key
     * @param null $default
     * @param false $returnObject
     * @return mixed
     */
    public function getMeta(MetableContract $metable, string $key, $default = null, $returnObject = false);

    /**
     * Обновляем мета тег.
     *
     * @param MetableContract $metable
     * @param string $key
     * @param $newValue
     * @return mixed
     */
    public function updateMeta(MetableContract $metable, string $key, $newValue);

    /**
     * Добавляем мета тег.
     *
     * @param MetableContract $metable
     * @param string $key
     * @param $value
     * @return mixed
     */
    public function addMeta(MetableContract $metable, string $key, $value);

    /**
     * Удаляем мета тег.
     *
     * @param MetableContract $metable
     * @param string $key
     * @return mixed
     */
    public function deleteMeta(MetableContract $metable, string $key);

    /**
     * Удаляем все мета теги.
     *
     * @param MetableContract $metable
     * @return mixed
     */
    public function deleteAllMeta(MetableContract $metable);
}
