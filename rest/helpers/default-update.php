<?php

return function($table) {
    $data   = $this->helper('sql-update-fomart')();
    $query  = $this->helper('sql-fomart-where')();
    $sql    = "UPDATE `{$table}` SET {$data->keys} WHERE {$query->keys}";
    $values = array_merge($data->values, $query->values);
    
    try {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($values);
        return $stmt->rowCount();
    } catch(PDOException $ex) {
        throw new Exception('Erro ao executar atualização: ' . $ex->getMessage(), 500);
    }
};