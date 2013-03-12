<?php

defined('T') or die("You cannot call this file on its own. Include index.php first.");

if (!defined('__DIR__')) {
	define('__DIR__', dirname(__FILE__));
}
if (!defined('MW_VERSION')) {
	define('MW_VERSION', 0.518);
}

set_include_path(MW_APPPATH_FULL . 'classes' . DS . PATH_SEPARATOR . MODULES_DIR . PATH_SEPARATOR . get_include_path());

function mw_autoload($className) {
	$className = ltrim($className, '\\');
	$fileName = '';
	$namespace = '';
	if ($lastNsPos = strripos($className, '\\')) {
		$namespace = substr($className, 0, $lastNsPos);
		$className = substr($className, $lastNsPos + 1);
		$fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
	}
	if ($className != '') {
		$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

		require  $fileName;
	}

}

spl_autoload_register('mw_autoload');

/*
 spl_autoload_register(function($className) {

 require (str_replace('\\', '/', ltrim($className, '\\')) . '.php');
 });
 */

// Basic system functions
function p($f) {
	return __DIR__ . strtolower(str_replace('_', '/', "/$f.php"));
}

function load_file($f) {
	return ( str_replace('..', '', $f));
	//return  strtolower ( str_replace ( '_', '/', "/$f.php" ) );
}

function v(&$v, $d = NULL) {
	return isset($v) ? $v : $d;
}

function c($k, $no_static = false) {

	if ($no_static == false) {
		static $c;
	} else {
		$c = false;
	};

	if (isset($c[$k])) {
		return $c[$k];
	} else {
		if (defined('MW_CONFIG_FILE') and MW_CONFIG_FILE != false and is_file(MW_CONFIG_FILE)) {
			//d(MW_CONFIG_FILE);
			//if (is_file(MW_CONFIG_FILE)) {
			include_once (MW_CONFIG_FILE);
			if (isset($config)) {
				$c = $config;
				if (isset($c[$k])) {

					return $c[$k];
				}
			}
		}
		//	}
		//d(MW_CONFIG_FILE);

	}
}

function d($v) {
	return dump($v);
}

function dump($v) {
	return '<pre>' . var_dump($v) . '</pre>';
}

function _log($m) {
	file_put_contents(__DIR__ . '/log/.' . date('Y-m-d'), time() . ' ' . getenv('REMOTE_ADDR') . " $m\n", FILE_APPEND);
}

function h($s) {
	return htmlspecialchars($s, ENT_QUOTES, 'utf-8');
}

function redirect($u = '', $r = 0, $c = 302) {
	header($r ? "Refresh:0;url=$u" : "Location: $u", TRUE, $c);
}

function registry($k, $v = null) {
	static $o;
	return (func_num_args() > 1 ? $o[$k] = $v : (isset($o[$k]) ? $o[$k] : NULL));
}

function utf8($s, $f = 'UTF-8') {
	return @iconv($f, $f, $s);
}
