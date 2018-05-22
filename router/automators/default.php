<?php

return function($table) {
    return [
        'get'    => $this->automator('default-select')($table),
        'put'    => $this->automator('default-update')($table),
        'post'   => $this->automator('default-insert')($table),
        'delete' => $this->automator('default-delete')($table),
    ];
};