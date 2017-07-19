<?php 
//echo "Hi! :D";
define('CORE_DIR',__DIR__);

use 	Phalcon\DI\FactoryDefault as DefaultDI,
	Phalcon\Mvc\Micro\Collection,
	Phalcon\Config\Adapter\Ini as IniConfig,
	Phalcon\Loader;


if( !is_array($loaderList) ) $loaderList = array();
$loaderList['PhalconRest'] = CORE_DIR."/";

$loader = new Loader();
$loader->registerNamespaces($loaderList)->register();

$di = new DefaultDI();
$di->set('collections', function(){
	return include(CORE_DIR.'/routeLoader.php');
});

$di->setShared('config', function() {
	return new IniConfig(ROOT_DIR."/config.ini");
});
$di->setShared('session', function(){
	$session = new \Phalcon\Session\Adapter\Files();
	$session->start();
	return $session;
});

$di->set('modelsCache', function() {
	$frontCache = new \Phalcon\Cache\Frontend\Data(array(
		'lifetime' => 0,//3600
	));

	//File cache settings
	$cache = new \Phalcon\Cache\Backend\File($frontCache, array(
		'cacheDir' => __DIR__ . '/cache/'
	));

	return $cache;
});

$di->set('db', function(){
	$conf = $this->get('config');
	if(isset($conf->database)){
		$dbConf = $conf->database->toArray();
		unset($dbConf['adapter']);
		$className = "\\Phalcon\\Db\\Adapter\\Pdo\\{$conf->database->adapter}";
		return new $className($dbConf);
	}
});
$di->setShared('requestBody', function() {
	$in = file_get_contents('php://input');
	$in = json_decode($in, FALSE);

	if($in === null){
		throw new HTTPException(
			'There was a problem understanding the data sent to the server by the application.',
			409,
			array(
				'dev' => 'The JSON body sent to the server was unable to be parsed.',
				'internalCode' => 'REQ1000',
				'more' => ''
			)
		);
	}

	return $in;
});

$app = new Phalcon\Mvc\Micro();
$app->setDI($di);
