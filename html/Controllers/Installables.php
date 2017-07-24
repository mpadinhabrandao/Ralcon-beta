<?php
namespace Controllers;

use \PhalconRest\Controllers\RESTBaseController;
use \PhalconRest\Exceptions\HTTPException;
use \Models\Installables as Model;
use \Models\Versions;


class Installables extends RESTBaseController{

	protected $model = null;
	
	public function __construct($parseQueryString = true){
		$this->model = new Model();
		$this->primaryKey = 'namespace';
		$this->allowedFields['orders'] = array('version','namespace','deleted');
		$this->allowedFields['search'] = array('version','namespace','deleted');
		$this->allowedFields['partials'] = array('version','namespace','deleted','lastEdit');
		parent::__construct();
	}
	public function getTreeRose($app, $deleted = 0){
		$array = array();
		$modelVersions = new Versions();
		
		$array['conditions'] = "deleted = :deleted: AND namespace = :namespace:";
		$array['bind']['namespace'] = $app;
		$array['bind']['deleted'] = $deleted;
		
		$itms = $modelVersions->find( $array );
		if( $itms ){
			$rose = array();
			foreach( $itms as $itm ){
				$numbers = ltrim($itm->version,'v');
				$numbers = explode('.',$numbers);
				$rose	[$numbers[0]]
						[$numbers[1]]
						[$numbers[2]] = $itm->version;//$itm->toArray();
			}
			return $rose;
			var_dump($rose);
			die();
			return $rose;
		}else{
			$err = array(array(
				"message" => "App $app without versions",
				"code" => "100004"
			));
			return $err;
		}
	}

}
