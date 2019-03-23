<?php

namespace InetStudio\Meta\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use InetStudio\Meta\Contracts\Models\MetaModelContract;

/**
 * Class MetaModel.
 */
class MetaModel extends Model implements MetaModelContract, Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;

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
     * Should the timestamps be audited?
     *
     * @var bool
     */
    protected $auditTimestamps = true;

    /**
     * Полиморфное отношение с остальными моделями.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function metable()
    {
        return $this->morphTo();
    }
}
