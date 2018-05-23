<?php

return [
    'pdo' => [
        'dsn'      => 'mysql:host=localhost;dbname=ia16b',
        'username' => 'root',
        'password' => ''
    ],

    'action' => [
        'default' => '__default'
    ],
    
    'folder' => [
        'action'    => Self::DIR . 'actions',
        'validator' => Self::DIR . 'validators',
        'helper'    => Self::DIR . 'helpers',
        'automator' => Self::DIR . 'automators',
    ]
];