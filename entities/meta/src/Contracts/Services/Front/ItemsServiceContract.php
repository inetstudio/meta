<?php

namespace InetStudio\MetaPackage\Meta\Contracts\Services\Front;

use Arcanedev\SeoHelper\Entities\Title;
use Arcanedev\SeoHelper\Entities\Keywords;
use Arcanedev\SeoHelper\Entities\MiscTags;
use Arcanedev\SeoHelper\Entities\Webmasters;
use Arcanedev\SeoHelper\Entities\Description;
use Arcanedev\SeoHelper\Entities\OpenGraph\Graph;

/**
 * Interface ItemsServiceContract.
 */
interface ItemsServiceContract
{
    /**
     * Получаем мета теги страницы.
     *
     * @param $item
     *
     * @return array
     */
    public function getAllTags($item): array;

    /**
     * Возвращаем CSRF мета тег.
     *
     * @return MiscTags
     */
    public function getCSRFMeta(): MiscTags;

    /**
     * Возвращаем заголовок.
     *
     * @param $item
     *
     * @return Title|null
     */
    public function getTitle($item): ?Title;

    /**
     * Возвращаем описание.
     *
     * @param $item
     *
     * @return Description|null
     */
    public function getDescription($item): ?Description;

    /**
     * Возвращаем тег индексации.
     *
     * @param $item
     *
     * @return MiscTags|null
     */
    public function getRobots($item): ?MiscTags;

    /**
     * Возвращаем ключевые слова.
     *
     * @param $item
     *
     * @return Keywords|null
     */
    public function getKeywords($item): ?Keywords;

    /**
     * Возвращаем теги верификации для вебмастеров.
     *
     * @return Webmasters
     */
    public function getWebmasters(): Webmasters;

    /**
     * Возвращаем Open Graph теги.
     *
     * @param $item
     *
     * @return Graph
     */
    public function getOpenGraph($item): Graph;
}
