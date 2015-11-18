<?php

/**
 * Bootloader
 *
 * @author    Devin Smith <devin@cana.la>
 * @date     2009.09.17
 *
 * The bootloader to include the luma application class
 *
 */

// keep the directory setup in here so we can change its path later

$GLOBALS['config'] = [
	'dirs' => [
		'controller'		=> dirname(__FILE__).'/controllers/',
		'config'			=> dirname(__FILE__).'/config/',
		'view'				=> dirname(__FILE__).'/views/',
		'library'			=> dirname(__FILE__).'/library/',
		'root'				=> dirname(__FILE__).'/../',
		'www'				=> dirname(__FILE__).'/../www/',
	],'libraries' 			=> ['App','Cana:../../../src']
];

// convert from globals to $config
spl_autoload_register(function ($className) {

	if (strpos($className, '\\') !== false) {
		$classes = explode('\\', $className);
		$dir = array_shift($classes);
		$classes = implode('\\', $classes);

		$className = str_replace('\\','/',$classes);

		$libraries = [$dir];
		$ignoreAlias = true;

	} else {
		$libraries = $GLOBALS['config']['libraries'];
	}

	$class = str_replace('_','/',$className);

	if (file_exists($GLOBALS['config']['dirs']['library'] . $class . '.php')) {
		require_once $GLOBALS['config']['dirs']['library'] . $class . '.php';
		return;
	}

	foreach ($libraries as $prefix) {

		$p = explode(':', $prefix);
		$prefix = $p[0];
		$path = $p[1] ? $p[1] : $p[0];

		$fileName = $GLOBALS['config']['dirs']['library'] . $path . '/' . $class . '.php';

		if (file_exists($fileName)) {
			require_once $fileName;
			if (!$ignoreAlias && strpos($className, $prefix) !== 0 && !class_exists($className)) {
				class_alias((strpos($prefix, '/') ? '' :  $prefix.'_') . $className, $className);
			}
			return;
		}
	}

	if (!getenv('PHPUNIT')) {
		throw new Cana_Exception_MissingLibrary('The file '.$GLOBALS['config']['dirs']['library'] . $className . '.php'.' does not exist');
		exit;
	}
});


// no reason to pass __url
if (!$_REQUEST['__url']) {
	$request = explode('?', $_SERVER['REQUEST_URI'], 2)[0];
	$dir = dirname($_SERVER['SCRIPT_NAME']);

	$base = substr($dir, -1) == '/' ? $dir : $dir.'/';

	$url = preg_replace('/^'.str_replace('/','\\/',''.$dir).'/','',$request);
	$url = substr($url, 0, 1) == '/' ? $url : '/'.$url;
	$_REQUEST['__url'] = substr($url, 1);
}


if (file_exists($GLOBALS['config']['dirs']['config'].'config.xml')) {
	$configFile = $GLOBALS['config']['dirs']['config'].'config.xml';
}


// init (construct) the static Caffeine application and display the page requested
Cana::init([
	'app' => 'App_App',
	'config' => (new Cana_Config($configFile))->merge($GLOBALS['config'])
]);

