<?php

return [

    'admin_email' => env('ADMIN_EMAIL', null),

    'discount' => env('DISCOUNT_PERCENT', 1),

    'currencies' => [
        'site_currency' => env('SITE_CURRENCY', null),
        'shop_currency' => env('SHOP_CURRENCY', null)
    ],

    'units' => [
        'pound_eq_gram' => env('POUND_EQ_GRAM', 1),
        'ounce_eq_gram' => env('OUNCE_EQ_GRAM', 1),
    ],

    'parser' => [
        'proxy' => env('PARSER_PROXY', null),
        'attempts' => env('PARSER_ATTEMPTS', 0),
    ]
];