<?php

return function($table) {
    $data = $this->helper('sql-insert-fomart')();
    $sql  = "INSERT INTO `{$table}`({$data->keys}) VALUES ({$data->keysValues})";
    
    try {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data->values);
        return $this->pdo->lastInsertId();
    } catch(PDOException $ex) {
        throw new \Rest\RestException(
            'Erro ao executar inserção: ' . $ex->getMessage(), 
            $ex->getCode(),
            500
        );
    }
};