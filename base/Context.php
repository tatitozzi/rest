<?php

namespace base;

class Context {
	protected $child;
	protected $dir;

	function __construct() {
		$this->child = new \ReflectionClass($this);
	}

	function __get($key) {
		return $this->{$key} ?? null;
	}
    
	function __set($key, $val) {
        if (is_callable($val))
        	$val = \Closure::bind($val, $this);
		$this->{$key} = $val;
	}

	function __call($key, $args) {
        if (is_callable($this->{$key}))
            return call_user_func_array($this->{$key}, $args);
        return $this->{$key} ?? null;
    }
}