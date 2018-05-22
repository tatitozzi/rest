<?php

return function(string $table) {
    $cnf = $this->helper('get-action-config-by-table')($table);
    $query = $cnf->query;
    $validate = $cnf->validate;
    $queryComposed = [];
    foreach(array_merge($query, $validate) as $key => $entry) {
        $queryComposed[$key] = [false, $entry[1]];
    }
    $queryComposed['p'] = [false, 'integer', 1];
    $queryComposed['r'] = [false, 'integer', 5];
    return [
        'query' => $queryComposed,
        'execute' => function() use ($table) {
            return $this->helper('default-select')(
                $table, 
                $this->query['p'], 
                $this->query['r']
            );
        }
    ];
};