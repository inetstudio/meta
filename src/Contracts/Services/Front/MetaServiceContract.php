<?php

namespace InetStudio\Meta\Contracts\Services\Front;

use Arcanedev\SeoHelper\Entities\Title;
use Arcanedev\SeoHelper\Entities\Keywords;
use Arcanedev\SeoHelper\Entities\MiscTags;
use Arcanedev\SeoHelper\Entities\Webmasters;
use Arcanedev\SeoHelper\Entities\Description;
use Arcanedev\SeoHelper\Entities\OpenGraph\Graph;

/**
 * Interface MetaServiceContract.
 */
interface MetaServiceContract
{
    /**
     * Получаем мета теги страницы.
     *
     * @param $object
     * @return array
     */
    public function getAllTags($object): array;

    /**
     * Возвращаем CSRF мета тег.
     *
     * @return MiscTags
     */
    public function getCSRFMeta(): MiscTags;

    /**
     * Возвращаем заголовок.
     *
     * @param $object
     * @return Title
     */
    public function getTitle($object): ?Title;

    /**
     * Возвращаем описание.
     *
     * @param $object
     * @return Description
     */
    public function getDescription($object): ?Description;

    /**
     * Возвращаем тег индексации.
     *
     * @param $object
     * @return MiscTags|null
     */
    public function getRobots($object): ?MiscTags;

    /**
     * Возвращаем ключевые слова.
     *
     * @param $object
     * @return Keywords|null
     */
    public function getKeywords($object): ?Keywords;

    /**
     * Возвращаем теги верификации для вебмастеров.
     *
     * @return Webmasters
     */
    public function getWebmasters(): Webmasters;

    /**
     * Возвращаем Open Graph теги.
     *
     * @param $object
     * @return Graph
     */
    public function getOpenGraph($object): Graph;
}
