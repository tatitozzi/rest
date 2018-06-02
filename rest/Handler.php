<?php

namespace Rest;

class Handler {
    const PS = DIRECTORY_SEPARATOR;
    const DIR = __DIR__ . Self::PS;
    
    protected
        $config,
        $proxyRouterAttrs;

    function __construct($config, $proxyRouterAttrs) {
        $this->config = $config;
        $this->proxyRouterAttrs = $proxyRouterAttrs;
    }

    function &__get($name) {
        return $this->proxyRouterAttrs[$name];
    }
}