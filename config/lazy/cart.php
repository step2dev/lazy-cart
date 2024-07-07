<?php

// config for Step2Dev/LazyCart
return [
    'user_model'          => 'App\Models\User',
    'table_prefix'        => 'lazy_', // table prefix for cart table
    'cart'                => [
        'days' => 30, // days to keep cart
        'cookie' => [
            'name' => 'cart_session',// session key for cart session
        ],
    ],
    'money_format'        => [
        'separator' => '.',
        'thousand'  => ',',
        'precision' => 2,
    ],
];
