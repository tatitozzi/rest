<?php

return function($table) {
    $query = [];
    $data = [];
    $tableInfo = $this->helper('get-table-info')($table);
    
    foreach($tableInfo as $column) {
        $key = $column['key'];
        $name = $column['name'];
        $settings = [];
        $settings[] = (bool)($column['null'] == "NO");
        $settings = array_merge($settings, json_decode($column['comment']) ?? ["any"]);
        if ($key == "PRI") {
            $query[$name] = $settings;
            continue;
        }
        $data[$name] = $settings;
    }

    return (Object)[
        "query" => $query,
        "body" => $data
    ];
};