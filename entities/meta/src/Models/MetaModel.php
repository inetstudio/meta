<?php

namespace InetStudio\MetaPackage\Meta\Models;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use InetStudio\MetaPackage\Meta\Contracts\Models\MetaModelContract;
use InetStudio\AdminPanel\Base\Models\Traits\Scopes\BuildQueryScopeTrait;

/**
 * Class MetaModel.
 */
class MetaModel extends Model implements MetaModelContract
{
    use Auditable;
    use SoftDeletes;
    use BuildQueryScopeTrait;

    /**
     * Тип сущности.
     */
    const ENTITY_TYPE = 'meta';

    /**
     * Should the timestamps be audited?
     *
     * @var bool
     */
    protected $auditTimestamps = true;

    /**
     * Связанная с моделью таблица.
     *
     * @var string
     */
    protected $table = 'meta';

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'metable_type',
        'metable_id',
        'key',
        'value',
    ];

    /**
     * Атрибуты, которые должны быть преобразованы в даты.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Загрузка модели.
     */
    protected static function boot()
    {
        parent::boot();

        self::$buildQueryScopeDefaults['columns'] = [
            'id',
            'metable_type',
            'metable_id',
            'key',
            'value',
        ];
    }

    /**
     * Сеттер атрибута metable_type.
     *
     * @param $value
     */
    public function setMetableTypeAttribute($value)
    {
        $this->attributes['metable_type'] = trim(strip_tags($value));
    }

    /**
     * Сеттер атрибута metable_id.
     *
     * @param $value
     */
    public function setMetableIdAttribute($value)
    {
        $this->attributes['metable_id'] = (int) trim(strip_tags($value));
    }

    /**
     * Сеттер атрибута key.
     *
     * @param $value
     */
    public function setKeyAttribute($value): void
    {
        $this->attributes['key'] = trim(strip_tags($value));
    }

    /**
     * Сеттер атрибута value.
     *
     * @param $value
     */
    public function setValueAttribute($value): void
    {
        $this->attributes['value'] = trim(strip_tags($value));
    }

    /**
     * Геттер атрибута type.
     *
     * @return string
     */
    public function getTypeAttribute(): string
    {
        return self::ENTITY_TYPE;
    }

    /**
     * Полиморфное отношение с остальными моделями.
     *
     * @return MorphTo
     */
    public function metable(): MorphTo
    {
        return $this->morphTo();
    }
}
