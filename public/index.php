<?php

error_reporting(E_ALL);
ini_set('display_errors',1);
ini_set('display_startup_errors',1);


use Phalcon\Mvc\Application;
use Phalcon\Config\Adapter\Ini as ConfigIni;

//$_GET['_url'] = '/contact/send';
//$_SERVER['REQUEST_METHOD'] = 'POST';

try {

	define('APP_PATH', realpath('..') . '/');
    define('CACHE_PATH', realpath('..') . '/cache/');
    

	/**
	 * Read the configuration
	 */
	$config = new ConfigIni(APP_PATH . 'app/config/config.ini');
    
    
	/**
	 * Auto-loader configuration
	 */
	require APP_PATH . 'app/config/loader.php';

	/**
	 * Load application services
	 */
	require APP_PATH . 'app/config/services.php';

	$application = new Application($di);

	echo $application->handle()->getContent();

} catch (Exception $e){
	echo $e->getMessage();
}
