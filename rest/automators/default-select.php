<?php

return function(string $table) {
    $cnf = $this->helper('get-action-config-by-table')($table);
    $query = $cnf->query;
    $body = $cnf->body;
    $queryComposed = [];
    foreach(array_merge($query, $body) as $key => $entry) {
        $queryComposed[$key] = [false, $entry[1]];
    }
    $queryComposed['p'] = [false, 'integer', 1];
    $queryComposed['r'] = [false, 'integer', 5];
    return [
        'query' => $queryComposed,
        'callback' => function() use ($table) {
            return $this->helper('default-select')(
                $table, 
                $this->query['p'], 
                $this->query['r']
            );
        }
    ];
};