<?php

return [

    'tags' => [
        'title' => [
            'meta' => [
                'title',
            ],
            'fields' => [
                'title',
            ],
        ],
        'description' => [
            'meta' => [
                'description',
            ],
            'fields' => [],
        ],
        'robots' => [
            'meta' => [
                'robots',
            ],
            'fields' => [],
        ],
        'keywords' => [
            'meta' => [
                'keywords',
            ],
            'fields' => [],
        ],
        'og_title' => [
            'meta' => [
                'og:title',
                'title',
            ],
            'fields' => [
                'title',
            ],
        ],
        'og_description' => [
            'meta' => [
                'og:description',
                'description',
            ],
            'fields' => [],
        ],
        'og_image' => [
            'og_image' => 'og_image_default',
            'preview' => 'preview_3_2',
        ],
    ],

    'trailing_slash' => false,
];
