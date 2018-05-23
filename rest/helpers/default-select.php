<?php

return function($table, $page, $rows) {
    unset($this->query['p']);
    unset($this->query['r']);
    $rows = abs($rows);
    $page = abs($page-1) * $rows;
    $q = $this->helper('sql-fomart-where')();
    $where = $q->keys ? " WHERE {$q->keys} " : " ";
    $sql  = "SELECT * FROM `{$table}`{$where}LIMIT {$page},{$rows}";
    try {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($q->values);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $ex) {
        throw new Exception('Erro ao executar busca: ' . $ex->getMessage(), 500);
    }
};