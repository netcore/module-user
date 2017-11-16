<?php

return [
    'socialite' => false,

    'auth-controller' => null, // Auth controller class path (::class)

    'socialite-providers' => [
        'google'   => false,
        'facebook' => false,
        'twitter'  => false,
        'linkedin' => false,
        'github'   => false,
    ],

    'allow' => [
        'delete' => true,
        'create' => true,
        'view'   => false,
    ]
];
