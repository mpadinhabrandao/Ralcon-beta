<?php
namespace Controllers;

use \PhalconRest\Controllers\RESTBaseController;
use \PhalconRest\Exceptions\HTTPException;
use \Models\Dependencies as Model;


class Dependencies extends RESTBaseController{

	public function __construct($parseQueryString = true){
		$this->model = new Model();
		parent::__construct();
	}
	public function getInstallableVersions($app, $version){
		
		$array = array();
		$array['conditions'] = "namespace = :namespace:";
		$array['bind']['namespace'] = $app;
		$array['conditions'] .= " AND version = :version:";
		$array['bind']['version'] = $version;
		
		$itms = $this->model->find( $array );
		if( $itms ){
			return $itms->toArray();
		}else{
			$err = array(array(
				"message" => "Version $app/$version dont have dependencies",
				"code" => "100004"
			));
			return $err;
		}
		
	}public function postDependencies($app, $version){
		$this->model->namespace = $app;
		$this->model->version = $version;
		return $this->save($this->model);
	}

}
