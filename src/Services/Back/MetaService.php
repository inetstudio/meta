<?php

namespace InetStudio\Meta\Services\Back;

use InetStudio\Meta\Contracts\Models\Traits\MetableContract;
use InetStudio\Meta\Contracts\Services\Back\MetaServiceContract as BackMetaServiceContract;

class MetaService implements BackMetaServiceContract
{
    /**
     * Проверяем наличие мета тега.
     *
     * @param MetableContract $metable
     * @param string $key
     * @return mixed
     */
    public function hasMeta(MetableContract $metable, string $key): bool
    {
        return $metable->meta()
            ->where('key', $key)
            ->exists();
    }

    /**
     * Получаем все мета теги.
     *
     * @param MetableContract $metable
     * @return mixed
     */
    public function getAllMeta(MetableContract $metable)
    {
        return $metable->meta()->pluck('value', 'key')->toArray();
    }

    /**
     * Получаем мета тег.
     *
     * @param MetableContract $metable
     * @param string $key
     * @param null $default
     * @param false $returnObject
     * @return mixed
     */
    public function getMeta(MetableContract $metable, string $key, $default = null, $returnObject = false)
    {
        $meta = $metable->meta()
            ->where('key', $key)
            ->first();

        if ($returnObject) {
            return $meta;
        }

        return ($meta) ? $meta->value : $default;
    }

    /**
     * Обновляем мета тег.
     *
     * @param MetableContract $metable
     * @param string $key
     * @param $newValue
     * @return mixed
     */
    public function updateMeta(MetableContract $metable, string $key, $newValue)
    {
        $meta = $this->getMeta($metable, $key, null, true);

        if ($meta == null) {
            return $this->addMeta($metable, $key, $newValue);
        }

        return $meta->update([
            'value' => $newValue
        ]);
    }

    /**
     * Добавляем мета тег.
     *
     * @param MetableContract $metable
     * @param string $key
     * @param $value
     * @return mixed
     */
    public function addMeta(MetableContract $metable, string $key, $value)
    {
        if (! $value) {
            return false;
        }

        $existing = $metable->meta()
            ->where('key', $key)
            ->where('value', $value)
            ->exists();

        if ($existing) {
            return false;
        }

        return $metable->meta()->create([
            'key' => $key,
            'value' => $value,
        ]);
    }

    /**
     * Удаляем мета тег.
     *
     * @param MetableContract $metable
     * @param string $key
     * @return mixed
     */
    public function deleteMeta(MetableContract $metable, string $key)
    {
        return $metable->meta()->where('key', $key)->delete();
    }

    /**
     * Удаляем все мета теги.
     *
     * @param MetableContract $metable
     * @return mixed
     */
    public function deleteAllMeta(MetableContract $metable)
    {
        return $metable->meta()->delete();
    }

    /**
     * Сохраняем мета теги.
     *
     * @param $request
     * @param MetableContract $metable
     */
    public function attachToObject($request, MetableContract $metable): void
    {
        if ($request->filled('meta')) {
            foreach ($request->get('meta') as $key => $value) {
                $metable->updateMeta($key, $value);
            }

            event(app()->makeWith('InetStudio\Meta\Contracts\Events\Back\UpdateMetaEventContract', [
                'object' => $metable,
            ]));
        }
    }
}
