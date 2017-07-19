<?php
namespace Controllers;

use \PhalconRest\Controllers\RESTBaseController;
use \PhalconRest\Exceptions\HTTPException;
use \Models\Installables as Model;


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

}
