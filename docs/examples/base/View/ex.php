<?php

include __DIR__ . "/../../../../autoloader.inc.php";

$view = new \Base\View(__DIR__ . "/ex.html");
$view->variavel = 'oiiieeee deu certo manolo';
$view->pessoasA = [];
$view->pessoas = [
    [
        'nome' => 'daniel',
        'apelido' => 'Drache',
        'filhos' => [
            ['idade' => 5, 'nome' => 'davi']
        ],
        'telefones' => [
            '47992022970',
            '44988055775'
        ]
    ],
    [
        'nome' => 'jussara',
        'apelido' => 'jus',
        'filhos' => [
            ['idade' => 18, 'nome' => 'mikaela'],
            ['idade' => 24, 'nome' => 'thais']
        ],
        'telefones' => [
            '11111111111',
            '22222222222'
        ]
    ],
    [
        'nome' => 'delminda',
        'apelido' => 'neisinha',
        'filhos' => [
            ['idade' => 32, 'nome' => 'daniel'],
            ['idade' => 35, 'nome' => 'ligia'],
            ['idade' => 38, 'nome' => 'solano']
        ],
        'telefones' => [
            '33333333333',
            '44444444444',
            '55555555555'
        ]
    ]
];

exit( $view );