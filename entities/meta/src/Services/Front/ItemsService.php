<?php

namespace InetStudio\MetaPackage\Meta\Services\Front;

use Arcanedev\SeoHelper\Entities\Title;
use Arcanedev\SeoHelper\Entities\Keywords;
use Arcanedev\SeoHelper\Entities\MiscTags;
use Arcanedev\SeoHelper\Entities\Webmasters;
use Arcanedev\SeoHelper\Entities\Description;
use Arcanedev\SeoHelper\Entities\OpenGraph\Graph;
use InetStudio\MetaPackage\Meta\Contracts\Services\Front\ItemsServiceContract;

/**
 * Class ItemsService.
 */
class ItemsService implements ItemsServiceContract
{
    /**
     * Получаем мета теги страницы.
     *
     * @param $object
     *
     * @return array
     */
    public function getAllTags($object): array
    {
        $tags = [];

        if ($object) {
            $tags['title'] = $this->getTitle($object);
            $tags['description'] = $this->getDescription($object);
            $tags['keywords'] = $this->getKeywords($object);
            $tags['robots'] = $this->getRobots($object);
            $tags['webmasters'] = $this->getWebmasters();
            $tags['openGraph'] = $this->getOpenGraph($object);
            $tags['canonical'] = $this->getCanonical($object);
        }

        $tags['csrf-token'] = $this->getCsrfMeta();

        return $tags;
    }

    /**
     * Возвращаем csrf мета тег.
     *
     * @return MiscTags
     */
    public function getCsrfMeta(): MiscTags
    {
        return new MiscTags(
            [
                'default' => [
                    'csrf-token' => csrf_token(),
                ],
            ]
        );
    }

    /**
     * Возвращаем заголовок.
     *
     * @param $object
     *
     * @return Title|null
     */
    public function getTitle($object): ?Title
    {
        $title = $this->getTagValue($object, 'title');

        return ($title) ? Title::make($title, '', '')->setLast()->setMax(999) : null;
    }

    /**
     * Возвращаем описание.
     *
     * @param $object
     *
     * @return Description|null
     */
    public function getDescription($object): ?Description
    {
        $description = $this->getTagValue($object, 'description');

        return ($description) ? Description::make($description)->setMax(999) : null;
    }

    /**
     * Возвращаем тег индексации.
     *
     * @param $object
     *
     * @return MiscTags|null
     */
    public function getRobots($object): ?MiscTags
    {
        $robots = $this->getTagValue($object, 'robots');

        return ($robots) ? new MiscTags(
            [
                'default' => [
                    'robots' => $robots,
                ],
            ]
        ) : null;
    }

    /**
     * Возвращаем ключевые слова.
     *
     * @param $object
     *
     * @return Keywords|null
     */
    public function getKeywords($object): ?Keywords
    {
        $keywords = $this->getTagValue($object, 'keywords');

        return ($keywords) ? Keywords::make($keywords) : null;
    }

    /**
     * Возвращаем теги верификации для вебмастеров.
     *
     * @return Webmasters
     */
    public function getWebmasters(): Webmasters
    {
        return Webmasters::make(
            [
                'google' => config('services.webmaster.google.verification_code', ''),
                'yandex' => config('services.webmaster.yandex.verification_code', ''),
            ]
        );
    }

    /**
     * Возвращаем Open Graph теги.
     *
     * @param $object
     *
     * @return Graph
     */
    public function getOpenGraph($object): Graph
    {
        $title = $this->getTagValue($object, 'og_title');
        $description = $this->getTagValue($object, 'og_description');
        $image = $this->getImagePath($object, 'og_image');

        $imageData = ($image != '')
            ? [
                'image' => $image,
            ]
            : [];

        $url = $this->getUrl($object);

        return new Graph(
            [
                'type' => 'website',
                'site-name' => config('app.name'),
                'title' => ($title) ? $title : '',
                'description' => ($description) ? $description : '',
                'properties' => array_merge(
                    [
                        'url' => $url,
                    ],
                    $imageData
                ),
            ]
        );
    }

    /**
     * Возвращаем canonical тег.
     *
     * @param $object
     *
     * @return MiscTags
     */
    public function getCanonical($object): MiscTags
    {
        $url = $this->getUrl($object);

        return new MiscTags(
            [
                'default' => [
                    'canonical' => $url,
                ],
            ]
        );
    }

    /**
     * Получаем значение тега по полям из конфига.
     *
     * @param $object
     * @param  string  $key
     *
     * @return string
     */
    protected function getTagValue($object, string $key): string
    {
        $data = config('meta.tags.'.$key, []);

        foreach ($data['meta'] ?? [] as $tagKey) {
            $meta = $object->meta->where('key', $tagKey)->first();

            if ($meta && $meta->value != '') {
                return $meta->value;
            }
        }

        foreach ($data['fields'] ?? [] as $objectField) {
            $value = $object[$objectField];

            if ($value) {
                return $value;
            }
        }

        return '';
    }

    /**
     * Получаем путь к изображению по полям из конфига.
     *
     * @param $object
     * @param  string  $key
     *
     * @return string
     */
    protected function getImagePath($object, string $key): string
    {
        $data = config('meta.tags.'.$key);

        foreach ($data ?? [] as $image => $conversions) {
            if (! $object->hasMedia($image)) {
                continue;
            }

            foreach ($conversions as $conversion) {
                if ($object->getFirstMedia($image)->hasGeneratedConversion($conversion)) {
                    return url($object->getFirstMediaUrl($image, $conversion));
                }
            }

            return url($object->getFirstMediaUrl($image));
        }

        return '';
    }

    /**
     * Получаем ссылку на страницу.
     *
     * @param $object
     *
     * @return string
     */
    protected function getUrl($object): string
    {
        $canonical = $this->getTagValue($object, 'canonical');

        if ($canonical) {
            $url = trim($canonical, '/').(config('meta.trailing_slash') ? '/' : '');
        } else {
            $url = ($object->slug == 'index')
                ? url('/')
                : url($object->href);

            $url = trim($url, '/');
            $url .= (config('meta.trailing_slash')) ? '/' : '';
            $url = str_replace('www.', '', $url);
        }

        return $url;
    }
}
