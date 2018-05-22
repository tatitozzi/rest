<?php


return [
    'get'    => $this->automator('default-select')('pessoa'),
    'put'    => $this->automator('default-update')('pessoa'),
    'post'   => $this->automator('default-insert')('pessoa'),
    'delete' => $this->automator('default-delete')('pessoa'),
];