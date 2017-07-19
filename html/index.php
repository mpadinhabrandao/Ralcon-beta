<?php

ini_set("display_errors","on");
ini_set("display_startup_errors","on");
error_reporting(E_ALL);

// Root dir define
define('ROOT_DIR',__DIR__);

$loaderList = array(
	'Models' => __DIR__ . '/Models/',
	'Controllers' => __DIR__ . '/Controllers/',
);

include ROOT_DIR.'/Core/Autoloader.php';

/*
$loader->registerNamespaces(array(
	'Models' => __DIR__ . '/Models/',
	'Controllers' => __DIR__ . '/Controllers/',
))->register();
*/


/**
 * Before every request, make sure user is authenticated.
 * Returning true in this function resumes normal routing.
 * Returning false stops any route from executing.
 */

/*
This will require changes to fit your application structure.
It supports Basic Auth, Session auth, and Exempted routes.

It also allows all Options requests, as those tend to not come with
cookies or basic auth credentials and Preflight is not implemented the
same in every browser.
*/

/*
$app->before(function() use ($app, $di) {

	// Browser requests, user was stored in session on login, replace into DI
	if ($di->getShared('session')->get('user') != false) {
		$di->setShared('user', function() use ($di){
			return $di->getShared('session')->get('user');
		});
		return true;
	}

	// Basic auth, for programmatic responses
	if($app->request->getServer('PHP_AUTH_USER')){
		$user = new \PhalconRest\Controllers\UsersController();
		$user->login(
			$app->request->getServer('PHP_AUTH_USER'),
			$app->request->getServer('PHP_AUTH_PW')
		);
		return true;
	}


	// All options requests get a 200, then die
	if($app->__get('request')->getMethod() == 'OPTIONS'){
		$app->response->setStatusCode(200, 'OK')->sendHeaders();
		exit;
	}


	// Exempted routes, such as login, or public info.  Let the route handler
	// pick it up.
	switch($app->getRouter()->getRewriteUri()){
		case '/users/login':
			return true;
			break;
		case '/example/route':
			return true;
			break;
	}

	// If we made it this far, we have no valid auth method, throw a 401.
	throw new \PhalconRest\Exceptions\HTTPException(
		'Must login or provide credentials.',
		401,
		array(
			'dev' => 'Please provide credentials by either passing in a session token via cookie, or providing password and username via BASIC authentication.',
			'internalCode' => 'Unauth:1'
		)
	);

	return false;
});*/


/**
 * Mount all of the collections, which makes the routes active.
 */
foreach($di->get('collections') as $collection){
	$app->mount($collection);
}


/**
 * The base route return the list of defined routes for the application.
 * This is not strictly REST compliant, but it helps to base API documentation off of.
 * By calling this, you can quickly see a list of all routes and their methods.
 */
$app->get('/', function() use ($app){
	$routes = $app->getRouter()->getRoutes();
	$routeDefinitions = array(
				'GET'=>array(), 
				'POST'=>array(), 
				'PUT'=>array(), 
				'PATCH'=>array(), 
				'DELETE'=>array(), 
				'HEAD'=>array(),
		 		'OPTIONS'=>array()
	);
	foreach($routes as $route){
		$method = $route->getHttpMethods();
		$routeDefinitions[$method][] = $route->getPattern();
	}
	return $routeDefinitions;
});

/**
 * After a route is run, usually when its Controller returns a final value,
 * the application runs the following function which actually sends the response to the client.
 *
 * The default behavior is to send the Controller's returned value to the client as JSON.
 * However, by parsing the request querystring's 'type' paramter, it is easy to install
 * different response type handlers.  Below is an alternate csv handler.
 */
$app->after(function() use ($app) {

	// OPTIONS have no body, send the headers, exit
	if($app->request->getMethod() == 'OPTIONS'){
		$app->response->setStatusCode('200', 'OK');
		$app->response->send();
		return;
	}

	// Respond by default as JSON
	if(!$app->request->getQuery('type') || $app->request->getQuery('type') == 'json'){

		// Results returned from the route's controller.  All Controllers should return an array
		$records = $app->getReturnedValue();

		$response = new \PhalconRest\Responses\JSONResponse();
		$response->useEnvelope(true) //this is default behavior
			->convertSnakeCase(true) //this is also default behavior
			->send($records);

		return;
	}
	else if($app->request->getQuery('type') == 'csv'){

		$records = $app->getReturnedValue();
		$response = new \PhalconRest\Responses\CSVResponse();
		$response->useHeaderRow(true)->send($records);

		return;
	}
	else {
		throw new \PhalconRest\Exceptions\HTTPException(
			'Could not return results in specified format',
			403,
			array(
				'dev' => 'Could not understand type specified by type paramter in query string.',
				'internalCode' => 'NF1000',
				'more' => 'Type may not be implemented. Choose either "csv" or "json"'
			)
		);
	}
});

$app->notFound(function () use ($app) {
	throw new \PhalconRest\Exceptions\HTTPException(
		'Not Found.',
		404,
		array(
			'dev' => 'That route was not found on the server.',
			'internalCode' => 'NF1000',
			'more' => 'Check route for misspellings.'
		)
	);
});

set_exception_handler(function($exception) use ($app){
	//HTTPException's send method provides the correct response headers and body
	if(is_a($exception, 'PhalconRest\\Exceptions\\HTTPException')){
		$exception->send();
	}
	error_log($exception);
	error_log($exception->getTraceAsString());
});

$app->handle();

