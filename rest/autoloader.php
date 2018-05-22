<?php

namespace rest;

spl_autoload_register(function ($class) {
    if (strpos($class, __NAMESPACE__) !== 0) 
        return;
        
    if (file_exists($file = realpath(__DIR__ .  "/../" . $class . '.php'))) 
        require $file;
});