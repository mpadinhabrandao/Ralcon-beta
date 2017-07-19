
<?php

return call_user_func(function(){

	$exampleCollection = new \Phalcon\Mvc\Micro\Collection();

	$exampleCollection
		->setPrefix('/v1/installables')
		->setHandler('\Controllers\Versions')
		->setLazy(true);

	$exampleCollection->get('/{app:[a-zA-Z0-9]+}/versions', 'getInstallableVersions');
	$exampleCollection
		->get(
			'/{app:[a-zA-Z0-9]+}/versions/{version:v[0-9]{1,}.[0-9]{1,}.[0-9]{1,}}', 
			'getInstallableVersions'
		);


	$exampleCollection->post('/{app:[a-zA-Z0-9]+}/versions', 'post');
	$exampleCollection
		->post(
			'/{app:[a-zA-Z0-9]+}/versions/{version:v[0-9]{1,}.[0-9]{1,}.[0-9]{1,}}', 
			'put'
		);
	
	$exampleCollection->delete('/{app:[a-zA-Z0-9]+}/versions/{version:v[0-9]{1,}.[0-9]{1,}.[0-9]{1,}}', 'delete');

	
	$exampleCollection->get(
		'/{app:[a-zA-Z0-9]+}/versions/{version:v[0-9]{1,}.[0-9]{1,}.[0-9]{1,}}/upload', 
		'upload'
	);
	return $exampleCollection;
});
