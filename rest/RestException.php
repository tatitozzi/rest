<?php

namespace rest;

class RestException extends \Exception {
    protected $customCode;
    protected $httpStatusCode;

    function __construct(string $message = "", string $customCode = '...', int $httpStatusCode = 200, Throwable $previous = NULL) {
        Parent::__construct($message, 0, $previous);
        $this->customCode = $customCode;
        $this->httpStatusCode = $httpStatusCode;
    }

    function getCustomCode() {
        return $this->customCode;
    }

    function getHttpStatusCode() {
        return $this->httpStatusCode;
    }
}