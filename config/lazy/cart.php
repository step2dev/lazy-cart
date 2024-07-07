<?php

// config for Step2Dev/LazyCart
return [
    'user_model'          => 'App\Models\User',
    'table_prefix'        => 'lazy_', // table prefix for cart table
    'default_session_key' => 'cart_session_id', // session key for cart session
    'cart'                => [
        'days' => 30, // days to keep cart session
    ],
    'money_format'        => [
        'separator' => '.',
        'thousand'  => ',',
        'precision' => 2,
    ],
];
