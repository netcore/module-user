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
        'export' => false,
    ],

    // Example export options config
    'export_options' => [
        // Many records.
        [
            'title'   => 'Users only',
            'filters' => [
                [
                    'name'     => 'Register date from:',
                    'key'      => 'created_at_from',
                    'type'     => 'date',
                    'field'    => 'created_at',
                    'operator' => '>=',
                    'required' => false,
                ],
                [
                    'name'     => 'Register date to:',
                    'key'      => 'created_at_to',
                    'type'     => 'date',
                    'field'    => 'created_at',
                    'operator' => '<=',
                    'required' => false,
                ],
                [
                    'name'           => 'Pick:',
                    'key'            => 'is_active',
                    'type'           => 'select',
                    'field'          => 'is_active',
                    'operator'       => '=',
                    'required'       => false,
                    'select_options' => [
                        null => 'Both',
                        0    => 'Inactive users',
                        1    => 'Active users',
                    ],
                ],
            ],
        ],

        // One record with related data.
        [
            'title'        => 'User with classifieds',
            'find_by'      => 'id:fullName',
            'withRelation' => [
                'name'    => 'classifieds',
                'filters' => [
                    [
                        'name'           => 'Of type:',
                        'key'            => 'of_type',
                        'type'           => 'select',
                        'field'          => 'type',
                        'operator'       => '=',
                        'required'       => false,
                        'select_options' => [
                            null   => 'All',
                            'buy'  => 'Buy',
                            'sell' => 'Sell',
                            'rent' => 'Rent',
                        ],
                    ],
                    [
                        'name'           => 'From date:',
                        'key'            => 'created_at_from',
                        'type'           => 'date',
                        'field'          => 'created_at',
                        'operator'       => '>=',
                        'required'       => false,
                    ],
                    [
                        'name'           => 'To date:',
                        'key'            => 'created_at_to',
                        'type'           => 'date',
                        'field'          => 'created_at',
                        'operator'       => '<=',
                        'required'       => false,
                    ],
                ],
            ],
        ],
    ],

];
