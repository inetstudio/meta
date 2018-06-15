<?php

namespace InetStudio\Meta\Services\Front;

use Illuminate\Support\Facades\Cache;
use Arcanedev\SeoHelper\Entities\Title;
use Arcanedev\SeoHelper\Entities\Keywords;
use Arcanedev\SeoHelper\Entities\MiscTags;
use Arcanedev\SeoHelper\Entities\Webmasters;
use Arcanedev\SeoHelper\Entities\Description;
use Arcanedev\SeoHelper\Entities\OpenGraph\Graph;
use InetStudio\Meta\Contracts\Services\Front\MetaServiceContract as FrontMetaServiceContract;

/**
 * Class MetaService.
 */
class MetaService implements FrontMetaServiceContract
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
            $cacheKey = 'MetaService_getAllTags_'.md5(get_class($object).$object->id);

            $tags = Cache::remember($cacheKey, 1440, function () use ($object) {
                $data = [];

                $data['title'] = $this->getTitle($object);
                $data['description'] = $this->getDescription($object);
                $data['keywords'] = $this->getKeywords($object);
                $data['robots'] = $this->getRobots($object);
                $data['webmasters'] = $this->getWebmasters();
                $data['openGraph'] = $this->getOpenGraph($object);
                $data['canonical'] = $this->getCanonical($object);

                return $data;
            });
        }

        $tags['csrf-token'] = $this->getCSRFMeta();

        return $tags;
    }

    /**
     * Возвращаем CSRF мета тег.
     *
     * @return MiscTags
     */
    public function getCSRFMeta(): MiscTags
    {
        return new MiscTags([
            'default' => [
                'csrf-token' => csrf_token(),
            ],
        ]);
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
        $cacheKey = 'MetaService_getTitle_'.md5(get_class($object).$object->id);

        return Cache::remember($cacheKey, 1440, function () use ($object) {
            $title = $this->getTagValue($object, 'title');

            return ($title) ? Title::make($title, '', '')->setLast()->setMax(999) : null;
        });
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
        $cacheKey = 'MetaService_getDescription_'.md5(get_class($object).$object->id);

        return Cache::remember($cacheKey, 1440, function () use ($object) {
            $description = $this->getTagValue($object, 'description');

            return ($description) ? Description::make($description)->setMax(999) : null;
        });
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
        $cacheKey = 'MetaService_getRobots_'.md5(get_class($object).$object->id);

        return Cache::remember($cacheKey, 1440, function () use ($object) {
            $robots = $this->getTagValue($object, 'robots');

            return ($robots) ? new MiscTags([
                'default'   => [
                    'robots' => $robots,
                ],
            ]) : null;
        });
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
        $cacheKey = 'MetaService_getKeywords_'.md5(get_class($object).$object->id);

        return Cache::remember($cacheKey, 1440, function () use ($object) {
            $keywords = $this->getTagValue($object, 'keywords');

            return ($keywords) ? Keywords::make($keywords) : null;
        });
    }

    /**
     * Возвращаем теги верификации для вебмастеров.
     *
     * @return Webmasters
     */
    public function getWebmasters(): Webmasters
    {
        return Webmasters::make([
            'yandex' => config('services.webmaster.yandex.verification_code'),
            'google' => config('services.webmaster.google.verification_code'),
        ]);
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
        $cacheKey = 'MetaService_getOpenGraph_'.md5(get_class($object).$object->id);

        return Cache::remember($cacheKey, 1440, function () use ($object) {
            $title = $this->getTagValue($object, 'og_title');
            $description = $this->getTagValue($object, 'og_description');
            $image = $this->getImagePath($object, 'og_image');

            return new Graph([
                'type' => 'website',
                'site-name' => config('app.name'),
                'title' => ($title) ? $title : '',
                'description' => ($description) ? $description : '',
                'properties' => [
                    'url' => ($object->slug == 'index') ? url('/') : url($object->href).(config('meta.trailing_slash') ? '/' : ''),
                    'image' => $image,
                    'image:width' => '968',
                    'image:height' => '475',
                ],
            ]);
        });
    }

    /**
     * Возвращаем canonical тег.
     *
     * @return MiscTags
     */
    public function getCanonical($object): MiscTags
    {
        $cacheKey = 'MetaService_getCanonical_'.md5(get_class($object).$object->id);

        return Cache::remember($cacheKey, 1440, function () use ($object) {
            return new MiscTags([
                'default' => [
                    'canonical' => ($object->slug == 'index') ? url('/') : url($object->href).(config('meta.trailing_slash') ? '/' : ''),
                ],
            ]);
        });
    }

    /**
     * Получаем значение тега по полям из конфига.
     *
     * @param $object
     * @param string $key
     *
     * @return string
     */
    private function getTagValue($object, string $key): string
    {
        $data = config('meta.tags.'.$key);

        if (! $data) {
            return '';
        }

        foreach ($data['meta'] as $tagKey) {
            $value = $object->getMeta($tagKey);

            if ($value) {
                return $value;
            }
        }

        foreach ($data['fields'] as $objectField) {
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
     * @param string $key
     *
     * @return string
     */
    private function getImagePath($object, string $key): string
    {
        $data = config('meta.tags.'.$key);

        if (! $data) {
            return '';
        }

        foreach ($data as $image => $conversion) {
            if (! $object->hasMedia($image)) {
                continue;
            }

            if ($conversion) {
                return url($object->getFirstMediaUrl($image, $conversion));
            } else {
                return url($object->getFirstMediaUrl($image));
            }
        }

        return '';
    }
}
