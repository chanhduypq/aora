<?php

return [
    'type' => [
        \App\Blacklist::TYPE_PRODUCT => 'Product',
        \App\Blacklist::TYPE_CATEGORY => 'Category'
    ],
    'status' => [
        \App\Blacklist::ACTIVE => 'Active',
        \App\Blacklist::INACTIVE => 'Inactive',
    ]
];