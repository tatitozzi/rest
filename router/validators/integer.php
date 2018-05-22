<?php

return function($value, $key, $alt=0) {
    if(empty($value))
        return filter_var($alt, FILTER_VALIDATE_INT);

    if (!is_integer(filter_var($value, FILTER_VALIDATE_INT)))
        throw new InvalidArgumentException("A chave `$key` deveria ser um número inteiro, mas foi definido como `".gettype($value)."` de valor `$value`.");

    return $value;
};