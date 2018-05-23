<?php

return [
    'system' => [
        'error' => [
            'trace' => false,
        ],
    ],

    'pdo' => [
        'dsn' => 'mysql:host=localhost;dbname=restoauth2ant-example-database',
        'username' => 'root',
        'password' => '',
    ],

    'action' => [
        'default' => '__default',
    ],

    'folder' =>
    [
        'action' => Self::DIR . 'actions',
        'validator' => Self::DIR . 'validators',
        'helper' => Self::DIR . 'helpers',
        'automator' => Self::DIR . 'automators',
        'cache' => Self::DIR . 'cache',
    ],
];
