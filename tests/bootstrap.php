<?php

namespace alfmarks;

require_once __DIR__ . '/../src/alfmarks.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/Mock.php';

function glob($pattern) {
	return Helper::glob($pattern);
}

Helper::filter('glob', function($pattern) {
	return array($pattern);
});

class Helper {

	public static $data = array();

	public static function setup() {
		static::$data = array();
	}

	public static function filter($method, $handler) {
		static::$data[$method] = $handler;
	}

	public static function __callStatic($method, $params) {
		return call_user_func_array(static::$data[$method], $params);
	}

}

