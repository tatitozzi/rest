<?php

return function(string $table) {
    $cnf = $this->helper('get-action-config-by-table')($table);
    return [
        'query' => $cnf->query,
        'body' => $cnf->validate,
        'callback' => function() use ($table) {
            return (int)$this->helper('default-update')($table);
        }
    ];
};