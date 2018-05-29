<?php

return [
    'pdo' => [
        'dsn' => 'mysql:host=localhost;dbname=restoauth2ant-example-database',
        'username' => 'root',
        'password' => '',
    ],

    'auth' => require $DIR . 'config-auth.php',

    'folder' => [
        'view' => $DIR . 'views'
    ]
];
