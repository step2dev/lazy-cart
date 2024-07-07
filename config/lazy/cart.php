<?php

// config for Step2Dev/LazyCart
return [
    'user_model'   => 'App\Models\User',
    'table_prefix' => 'lazy_', // table prefix for cart table
    'cart'         => [
        'prune_days' => 30, // days to keep cart
        'name'       => 'cart_session', // name of the cart session
    ],
    'money_format' => [
        'separator' => '.',
        'thousand'  => ',',
        'precision' => 2,
    ],
];
