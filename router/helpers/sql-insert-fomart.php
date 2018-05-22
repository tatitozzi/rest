<?php

return function() {
    $keys=[];
    $data=[];
    foreach($this->data as $key => $value) {
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