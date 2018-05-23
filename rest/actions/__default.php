<?php

$action = $this->action['name'];

if( basename(__FILE__) == "{$action}.php" )
    throw new \rest\RestException(
        'Este é um action genérico para todas as tabelas, você não pode acessa-lo diretamente.', 
        0000,
        403
    );

if ( empty($this->helper('get-table-info')($action)) )
    throw new \rest\RestException(
        "Este é um action genérico para todas as tabelas, e não foi possível encontrar a tabela `{$action}` no banco de dados.", 
        0000, 
        404
    );

return $this->automator('default')( $action );