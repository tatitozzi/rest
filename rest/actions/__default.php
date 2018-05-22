<?php

$action = $this->action['name'];

if( basename(__FILE__) == "{$action}.php" )
    throw new Exception('Este é um action genérico para todas as tabelas, você não pode acessa-lo diretamente.', 403);

if ( empty($this->helper('get-table-info')($action)) )
    throw new Exception("Este é um action genérico para todas as tabelas, e não foi possível encontrar a tabela `{$action}` no banco de dados.", 404);

return $this->automator('default')( $action );