<?php

namespace oauth;

class OAuth2 extends \base\Main {
    const DIR = __DIR__ . DIRECTORY_SEPARATOR;

    function __construct() {
        Parent::__construct();
        $this->loadConfig(new \base\Context);
        $this->parseActionInfo();
        if ($this->actionInfo['name'] == 'authorize')
            return $this->showAuthForm();
    }
    
    protected function showAuthForm() {
        $view = new \base\View("{$this->config['folder']['view']}/auth.html");
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
        exit( $view->getDOM()->saveHTML() );
    }
}