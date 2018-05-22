<?php

return function(string $table) {
    $cnf = $this->helper('get-action-config-by-table')($table);
    return [
        'query' => $cnf->query,
        'execute' => function() use ($table) {
            return (int)$this->helper('default-delete')($table);
        }
    ];
};