<?php

return function() {
    $keys=[];
    $data=[];
    foreach($this->body as $key => $value) {
        if (!$value) continue;
        $keys[] = $key;
        $data[$key] = $value;
    }
    return (Object)[
        'keys' => implode(', ', $keys),
        'keysValues' => ':'.implode(', :', $keys),
        'values' => $data
    ];
};