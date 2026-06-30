<?php

return [

    'default' => 'main',

    'connections' => [
        'main' => [
            'salt'     => env('HASHIDS_SALT', 'borobhai-online-default-salt'),
            'length'   => 12,
            'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',
        ],
    ],

];