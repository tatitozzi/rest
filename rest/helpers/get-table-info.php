<?php

return function(string $table) {
    $sql = "SELECT
        -- table_schema, 
        column_key     as `key`,
        column_comment as `comment`,
        column_name    as `name`,
        data_type      as `type`,
        is_nullable    as `null`,
        column_default as `default`
    FROM 
        information_schema.columns 
    WHERE 
        table_name = :table 
        AND table_schema = DATABASE()";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute(['table' => $table]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
};