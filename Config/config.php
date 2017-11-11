<?php

return [
    'socialite' => false,

    'auth-controller' => '\Auth\AuthController',

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
