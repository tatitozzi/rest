<?php

return [

    'get' => [
        'query' => [
            'id' => [false, 'integer'],
            'p'  => [false, 'integer', 1],
            'r'  => [false, 'integer', 10]   
        ],
        'callback' => function() {
            return $this->helper('default-select')('pessoa', $this->query['p'], $this->query['r']);
        }
    ],

    'post' => [
        'body' => [
            'nome'     => [true , 'nome-completo'],
            'telefone' => [false, 'any'],
            'email'    => [true , 'any']
        ],
        'callback' => function() {
            $id = $this->helper('default-insert')('pessoa');
            return "`{$this->body['nome']}` inserido com sucesso, com o id: $id";
        }
    ],

    'put' => [
        'query' => [
            'id' => [true, 'integer']
        ],
        'body' => [
            'nome'     => [true , 'nome-completo'],
            'telefone' => [false, 'any'],
            'email'    => [true , 'any']
        ],
        'callback' => function() {
            $qtd = $this->helper('default-update')('pessoa');
            return "$qtd fulano(s) alterado(s) com sucesso";
        }
    ],

    'delete' => [
        'query' => [
            'id' => [true, 'integer']
        ],
        'callback' => function() {
            $qtd = $this->helper('default-delete')('pessoa');
            return "$qtd fulano(s) deletado(s) com sucesso";
        }
    ]

];