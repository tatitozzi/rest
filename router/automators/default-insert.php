<?php

return function(string $table) {
    $cnf = $this->helper('get-action-config-by-table')($table);
    return [
        'validate' => $cnf->validate,
        'execute' => function() use ($table) {
            return (int)$this->helper('default-insert')($table);
        }   
    ];
};