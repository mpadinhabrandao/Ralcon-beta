<?php

return call_user_func(function(){

	$exampleCollection = new \Phalcon\Mvc\Micro\Collection();

	$exampleCollection
		->setPrefix('/v1/installables')
		->setHandler('\Controllers\Dependencies')
		->setLazy(true);

	$exampleCollection->get('/{app:[a-zA-Z0-9]+}/versions/{version:v[0-9]{1,}.[0-9]{1,}.[0-9]{1,}}/dependencies', 'getInstallableVersions');
	


	$exampleCollection->post('/{app:[a-zA-Z0-9]+}/versions/{version:v[0-9]{1,}.[0-9]{1,}.[0-9]{1,}}/dependencies', 'postDependencies');
	
	return $exampleCollection;
});
