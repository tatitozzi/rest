<?php

return [

    'get' => [
        'query' => [
            'id' => [false, 'integer'],
            'p'  => [false, 'integer', 1],
            'r'  => [false, 'integer', 10]   
        ],
        'execute' => function() {
            return $this->helper('default-select')('pessoa', $this->query['p'], $this->query['r']);
        }
    ],

    'post' => [
        'validate' => [
            'nome'     => [true , 'nome-completo'],
            'telefone' => [false, 'any'],
            'email'    => [true , 'any']
        ],
        'execute' => function() {
            $id = $this->helper('default-insert')('pessoa');
            return "`{$this->data['nome']}` inserido com sucesso, com o id: $id";
        }
    ],

    'put' => [
        'query' => [
            'id' => [true, 'integer']
        ],
        'validate' => [
            'nome'     => [true , 'nome-completo'],
            'telefone' => [false, 'any'],
            'email'    => [true , 'any']
        ],
        'execute' => function() {
            $qtd = $this->helper('default-update')('pessoa');
            return "$qtd fulano(s) alterado(s) com sucesso";
        }
    ],

    'delete' => [
        'query' => [
            'id' => [true, 'integer']
        ],
        'execute' => function() {
            $qtd = $this->helper('default-delete')('pessoa');
            return "$qtd fulano(s) deletado(s) com sucesso";
        }
    ]

];