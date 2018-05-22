<?php

return function($concat=" AND ") {
    $keys=[];
    $data=[];
    foreach($this->query as $key => $value) {
        if (!$value) continue;
        $keys[] = "$key=:$key";
        $data[$key] = $value;
    }
    return (Object)[
        'keys' => implode($concat, $keys),
        'values' => $data
    ];
};