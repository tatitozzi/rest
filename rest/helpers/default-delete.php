<?php

return function($table) {
    $query  = $this->helper('sql-fomart-where')();
    $sql    = "DELETE FROM `{$table}` WHERE {$query->keys}";
    
    try {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($query->values);
        return $stmt->rowCount();
    } catch(PDOException $ex) {
        throw new \Rest\RestException(
            'Erro ao executar exclusão: ' . $ex->getMessage(), 
            $ex->getCode(),
            500
        );
    }
};