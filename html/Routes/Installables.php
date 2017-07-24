<?php

return call_user_func(function(){

	$exampleCollection = new \Phalcon\Mvc\Micro\Collection();

	$exampleCollection
		->setPrefix('/v1/installables')
		->setHandler('\Controllers\Installables')
		->setLazy(true);


	$exampleCollection->get('/', 'get');

	$exampleCollection->get('/{app:[a-zA-Z0-9]+}', 'getOne');
	$exampleCollection->get('/{app:[a-zA-Z0-9]+}/rose', 'getTreeRose');
	
	$exampleCollection->post('/', 'post');
	$exampleCollection->post('/{app:[a-zA-Z0-9]+}', 'put');
	$exampleCollection->delete('/{app:[a-zA-Z0-9]+}', 'delete');

	return $exampleCollection;
});
