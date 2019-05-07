<?php

return [

    /*
     * Данные для подтверждения владения сайтом.
     */

    'webmaster' => [
        'google' => [
            'verification_code' => env('GOOGLE_WEBMASTER_VERIFY_CODE'),
        ],
        'yandex' => [
            'verification_code' => env('YANDEX_WEBMASTER_VERIFY_CODE'),
        ],
    ],
];
