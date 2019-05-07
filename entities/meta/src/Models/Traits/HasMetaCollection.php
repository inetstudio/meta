<?php

namespace InetStudio\MetaPackage\Meta\Models\Traits;

use ArrayAccess;
use Illuminate\Support\Collection;
use InetStudio\MetaPackage\Meta\Contracts\Models\MetaModelContract;

/**
 * Trait HasMetaCollection.
 */
trait HasMetaCollection
{
    /**
     * Determine if the model has any the given meta.
     *
     * @param  int|string|array|ArrayAccess|MetaModelContract  $meta
     *
     * @return bool
     */
    public function hasMeta($meta): bool
    {
        if ($this->isMetaStringBased($meta)) {
            return ! $this->meta->pluck('key')->intersect((array) $meta)->isEmpty();
        }

        if ($this->isMetaIntBased($meta)) {
            return ! $this->meta->pluck('id')->intersect((array) $meta)->isEmpty();
        }

        if ($meta instanceof MetaModelContract) {
            return $this->meta->contains('key', $meta['key']);
        }

        if ($meta instanceof Collection) {
            return ! $meta->intersect($this->meta->pluck('key'))->isEmpty();
        }

        return false;
    }

    /**
     * Determine if the model has any the given meta.
     *
     * @param  int|string|array|ArrayAccess|MetaModelContract  $meta
     *
     * @return bool
     */
    public function hasAnyMeta($meta): bool
    {
        return $this->hasMeta($meta);
    }

    /**
     * Determine if the model has all of the given meta.
     *
     * @param  int|string|array|ArrayAccess|MetaModelContract  $meta
     *
     * @return bool
     */
    public function hasAllMeta($meta): bool
    {
        if ($this->isMetaStringBased($meta)) {
            $meta = (array) $meta;

            return $this->meta->pluck('key')->intersect($meta)->count() == count($meta);
        }

        if ($this->isMetaIntBased($meta)) {
            $meta = (array) $meta;

            return $this->meta->pluck('id')->intersect($meta)->count() == count($meta);
        }

        if ($meta instanceof MetaModelContract) {
            return $this->meta->contains('key', $meta['key']);
        }

        if ($meta instanceof Collection) {
            return $this->meta->intersect($meta)->count() == $meta->count();
        }

        return false;
    }

    /**
     * Determine if the given meta are string based.
     *
     * @param  int|string|array|ArrayAccess|MetaModelContract  $meta
     *
     * @return bool
     */
    protected function isMetaStringBased($meta): bool
    {
        return is_string($meta) || (is_array($meta) && isset($meta[0]) && is_string($meta[0]));
    }

    /**
     * Determine if the given meta are integer based.
     *
     * @param  int|string|array|ArrayAccess|MetaModelContract  $meta
     *
     * @return bool
     */
    protected function isMetaIntBased($meta): bool
    {
        return is_int($meta) || (is_array($meta) && isset($meta[0]) && is_int($meta[0]));
    }
}
