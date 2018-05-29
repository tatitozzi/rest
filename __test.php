<?php

class Context {
	public function __get($key) {
		return $this->{$key} ?? null;
	}

	public function __set($key, $val) {
		if (is_callable($val))
			$val = \Closure::bind($val, $this);
		$this->{$key} = $val;
	}

	public function __call($key, $args) {
        return call_user_func_array($this->{$key}, $args);
    }
}

class X {

	protected $x = 100;
	protected $context;

	function __construct() {
		$this->context = new Context;
	}

	function fn1() {
		echo 'aaaaaaaaaaaa';
	}

	function fn2() {
		return Closure::bind(function() {

		}, (Object)[]);
	}

}

$x = new X();