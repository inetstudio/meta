<?php

namespace InetStudio\MetaPackage\Meta\Models\Traits;

use ArrayAccess;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Contracts\Container\BindingResolutionException;
use InetStudio\MetaPackage\Meta\Contracts\Models\MetaModelContract;

/**
 * Trait HasMeta.
 */
trait HasMeta
{
    use HasMetaCollection;

    /**
     * The queued meta.
     *
     * @var array
     */
    protected $queuedMeta = [];

    /**
     * Get Meta class name.
     *
     * @return string
     *
     * @throws BindingResolutionException
     */
    public function getMetaClassName(): string
    {
        $model = app()->make(MetaModelContract::class);

        return get_class($model);
    }

    /**
     * Получаем все мета теги материала.
     *
     * @return MorphMany
     *
     * @throws BindingResolutionException
     */
    public function meta(): MorphMany
    {
        $className = $this->getMetaClassName();

        return $this->morphMany($className, 'metable');
    }

    /**
     * Attach the given meta to the model.
     *
     * @param  int|string|array|ArrayAccess|MetaModelContract  $meta
     *
     * @throws BindingResolutionException
     */
    public function setMetaAttribute($meta): void
    {
        if (! $this->exists) {
            $this->queuedMeta = $meta;

            return;
        }

        $this->attachMeta($meta);
    }

    /**
     * Boot the HasMeta trait for a model.
     */
    public static function bootHasMeta()
    {
        static::created(
            function (Model $metableModel) {
                if ($metableModel->queuedMeta) {
                    $metableModel->attachMeta($metableModel->queuedMeta);
                    $metableModel->queuedMeta = [];
                }
            }
        );

        static::deleted(
            function (Model $metableModel) {
                $metableModel->syncMeta(null);
            }
        );
    }

    /**
     * Get the meta list.
     *
     * @return array
     *
     * @throws BindingResolutionException
     */
    public function getMetaList(): array
    {
        return $this->meta()
            ->pluck('value', 'key')
            ->toArray();
    }

    /**
     * Получаем мета тег.
     *
     * @param  string  $key
     * @param $default
     * @param  bool  $returnObject
     *
     * @return mixed|null
     *
     * @throws BindingResolutionException
     */
    public function getMeta(string $key, $default = null, bool $returnObject = false)
    {
        $builder = $this->meta()
            ->where('key', $key);

        if ($returnObject) {
            return $builder->withTrashed()->first();
        } else {
            $meta = $builder->first();
        }

        return ($meta) ? $meta->value : $default;
    }

    /**
     * Scope query with all the given meta.
     *
     * @param  Builder  $query
     * @param  int|string|array|ArrayAccess|MetaModelContract  $meta
     *
     * @return Builder
     *
     * @throws BindingResolutionException
     */
    public function scopeWithAllMeta(Builder $query, $meta): Builder
    {
        $meta = $this->isMetaStringBased($meta)
            ? $meta : $this->hydrateMeta($meta)->pluck('key')->toArray();

        collect($meta)->each(
            function ($metaItem) use ($query) {
                $query->whereHas(
                    'meta',
                    function (Builder $query) use ($metaItem) {
                        return $query->where('key', $metaItem);
                    }
                );
            }
        );

        return $query;
    }

    /**
     * Scope query with any of the given meta.
     *
     * @param  Builder  $query
     * @param  int|string|array|ArrayAccess|MetaModelContract  $meta
     *
     * @return Builder
     *
     * @throws BindingResolutionException
     */
    public function scopeWithAnyMeta(Builder $query, $meta): Builder
    {
        $meta = $this->isMetaStringBased($meta)
            ? $meta : $this->hydrateMeta($meta)->pluck('key')->toArray();

        return $query->whereHas(
            'meta',
            function (Builder $query) use ($meta) {
                $query->whereIn('key', (array) $meta);
            }
        );
    }

    /**
     * Scope query with any of the given meta.
     *
     * @param  Builder  $query
     * @param  int|string|array|ArrayAccess|MetaModelContract  $meta
     *
     * @return Builder
     *
     * @throws BindingResolutionException
     */
    public function scopeWithMeta(Builder $query, $meta): Builder
    {
        return $this->scopeWithAnyMeta($query, $meta);
    }

    /**
     * Scope query without the given meta.
     *
     * @param  Builder  $query
     * @param  int|string|array|ArrayAccess|MetaModelContract  $meta
     *
     * @return Builder
     *
     * @throws BindingResolutionException
     */
    public function scopeWithoutMeta(Builder $query, $meta): Builder
    {
        $meta = $this->isMetaStringBased($meta)
            ? $meta : $this->hydrateMeta($meta)->pluck('key')->toArray();

        return $query->whereDoesntHave(
            'meta',
            function (Builder $query) use ($meta) {
                $query->whereIn('key', (array) $meta);
            }
        );
    }

    /**
     * Scope query without any meta.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeWithoutAnyMeta(Builder $query): Builder
    {
        return $query->doesntHave('meta');
    }

    /**
     * Attach the given meta to the model.
     *
     * @param  int|string|array|ArrayAccess|MetaModelContract  $meta
     *
     * @return $this
     *
     * @throws BindingResolutionException
     */
    public function attachMeta($meta): self
    {
        static::$dispatcher->dispatch('inetstudio.meta.attaching', [$this, $meta]);

        foreach ($meta ?? [] as $key => $value) {
            $this->updateMeta($key, $value);
        }

        static::$dispatcher->dispatch('inetstudio.meta.attached', [$this, $meta]);

        return $this;
    }

    /**
     * Sync the given meta to the model.
     *
     * @param  int|string|array|ArrayAccess|MetaModelContract|null  $meta
     *
     * @return $this
     *
     * @throws BindingResolutionException
     */
    public function syncMeta($meta): self
    {
        static::$dispatcher->dispatch('inetstudio.meta.syncing', [$this, $meta]);

        foreach ($meta ?? [] as $key => $value) {
            if ($value === '') {
                $this->deleteMeta($key);
            } else {
                $this->updateMeta($key, $value);
            }
        }

        static::$dispatcher->dispatch('inetstudio.meta.synced', [$this, $meta]);

        return $this;
    }

    /**
     * Detach the given meta from the model.
     *
     * @param  int|string|array|ArrayAccess|MetaModelContract  $meta
     *
     * @return $this
     *
     * @throws BindingResolutionException
     */
    public function detachMeta($meta): self
    {
        static::$dispatcher->dispatch('inetstudio.meta.detaching', [$this, $meta]);

        foreach ($meta ?? [] as $key => $value) {
            $this->deleteMeta($key);
        }

        static::$dispatcher->dispatch('inetstudio.meta.detached', [$this, $meta]);

        return $this;
    }

    /**
     * Hydrate meta.
     *
     * @param  int|string|array|ArrayAccess|MetaModelContract  $meta
     *
     * @return Collection
     *
     * @throws BindingResolutionException
     */
    protected function hydrateMeta($meta): Collection
    {
        $isMetaStringBased = $this->isMetaStringBased($meta);
        $isMetaIntBased = $this->isMetaIntBased($meta);
        $field = $isMetaStringBased ? 'key' : 'id';
        $className = $this->getMetaClassName();

        return $isMetaStringBased || $isMetaIntBased
            ? $className::query()->whereIn($field, (array) $meta)->get() : collect($meta);
    }

    /**
     * Обновляем мета тег.
     *
     * @param  string  $key
     * @param $newValue
     *
     * @return mixed
     *
     * @throws BindingResolutionException
     */
    protected function updateMeta($key, $newValue)
    {
        $meta = $this->getMeta($key, null, true);

        if ($meta === null) {
            return $this->addMeta($key, $newValue);
        }

        if ($meta->trashed()) {
            $meta->restore();
        }

        return $meta->update(
            [
                'value' => $newValue,
            ]
        );
    }

    /**
     * Добавляем мета тег.
     *
     * @param  string  $key
     * @param $value
     *
     * @return mixed
     *
     * @throws BindingResolutionException
     */
    protected function addMeta($key, $value)
    {
        if (! $value) {
            return false;
        }

        $existing = $this->meta()
            ->where('key', $key)
            ->where('value', $value)
            ->exists();

        if ($existing) {
            return false;
        }

        return $this->meta()->create(
            [
                'key' => $key,
                'value' => $value,
            ]
        );
    }

    /**
     * Удаляем мета тег.
     *
     * @param  string  $key
     *
     * @return mixed
     *
     * @throws BindingResolutionException
     */
    protected function deleteMeta($key)
    {
        return $this->meta()
            ->where('key', $key)
            ->delete();
    }
}
