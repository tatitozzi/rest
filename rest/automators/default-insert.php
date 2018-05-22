<?php

return function(string $table) {
    $cnf = $this->helper('get-action-config-by-table')($table);
    return [
        'body' => $cnf->body,
        'callback' => function() use ($table) {
            return (int)$this->helper('default-insert')($table);
        }   
    ];
};