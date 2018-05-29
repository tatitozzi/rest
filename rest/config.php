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

    'folder' => [
        'action' => $DIR . 'actions',
        'validator' => $DIR . 'validators',
        'helper' => $DIR . 'helpers',
        'automator' => $DIR . 'automators',
        'cache' => $DIR . 'cache',
    ],
];
