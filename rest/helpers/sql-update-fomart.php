<?php

return function() {
    $keys=[];
    $data=[];
    foreach($this->body as $key => $value) {
        if (!$value) continue;
        $keys[] = "$key=:$key";
        $data[$key] = $value;
    }
    return (Object)[
        'keys' => implode(', ', $keys),
        'values' => $data
    ];
};