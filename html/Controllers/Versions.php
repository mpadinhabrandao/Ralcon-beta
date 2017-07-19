<?php
namespace Controllers;

use \PhalconRest\Controllers\RESTController;
use \PhalconRest\Exceptions\HTTPException;
use \Phalcon\Http\Request;
use \Models\Versions as Model;


class Versions extends RESTController{

	public function __construct($parseQueryString = true){
		$this->model = new Model();
		$this->allowedFields['search'] = array('namespace','version','deleted');
		$this->allowedFields['partials'] = array('namespace','version');
		parent::__construct();
	}
	
	public function getInstallableVersions( $app, $version = null ){
		
		$array = array();
		$find = "find";
		
		$array['conditions'] = "namespace = :namespace:";
		$array['bind']['namespace'] = $app;
		if( !is_null($version) ){
			$array['conditions'] .= " AND version = :version:";
			$array['bind']['version'] = $version;
			$find = "findFirst";
		}
		
		$itms = $this->model->$find( $array );
		if( $itms ){
			return $itms->toArray();
		}else{
			$err = array(array(
				"message" => "Version $version not found for Application $app",
				"code" => "100004"
			));
			return $err;
		}
	}
	public function delete($app, $version){
		
		$array = array();
		$array['conditions'] = "namespace = :namespace: AND version = :version:";
		$array['bind']['namespace'] = $app;
		$array['bind']['version'] = $version;
		
		$itm = $this->model->findFirst($array);
		
		if( $itm ){
			if( $itm->delete() ){
				return array(true);
			} else {
				$err = array();
				foreach ($itm->getMessages() as $message) {
				    $err[] = array(
						"message" => $message->getMessage(),
						"code" => $message->getCode()
					);
				}
				return $err;
			}
		}else{
			$err = array(array(
				"message" => "Item not found",
				"code" => "100002"
			));
			return $err;
		}
	}

	public function post($app){
		$this->model->namespace = $app;
		return $this->save($this->model);
	}

	public function put($app, $version){
		
		$array = array();
		$array['conditions'] = "namespace = :namespace: AND version = :version:";
		$array['bind']['namespace'] = $app;
		$array['bind']['version'] = $version;
		
		$itm = $this->model->findFirst($array);
		if( $itm ){
			return $this->save($itm);
		}else{
			$err = array(array(
				"message" => "Item not found",
				"code" => "100002"
			));
			return $err;
		}
		
	}
	

	private function save($model){
	
		$request = new Request();
		$data = $request->getPost();
		try {
			if($model->save($data)){
				return $model->toArray();
			} else {
				$err = array();
				foreach ($model->getMessages() as $message) {
			    		$err[] = array(
						"message" => $message->getMessage(),
						"code" => $message->getCode()
					);
				}
				return $err;
			}
		} catch ( \PDOException $e ) {
			return array(
				array(
					"message" => $e->getMessage(),
					"code" => $e->getCode()
				));
    		}
	}

	public function upload($app, $version){
		
		$array = array();
		$array['conditions'] = "namespace = :namespace: AND version = :version:";
		$array['bind']['namespace'] = $app;
		$array['bind']['version'] = $version;
		
		$itm = $this->model->findFirst($array);
		if( $itm ){
			$app = \Models\Installables::findFirst(array(
				'conditions' => 'namespace= :namespace:',
				'bind' => array('namespace' => $app)
			));
			//var_dump($app);
			if( $app ){
				$script = ROOT_DIR.'/Cmd/gitCopyVersion';
				$script = "$script {$app->git_url} {$itm->version}";
				var_dump($script);
				var_dump(shell_exec($script));
				die();
				if(empty($app->git_url)){
					$err = array(array(
						"message" => "App without  git url",
						"code" => "100003"
					));
					return $err;
				}
				echo exec('whoami'); 
			}else{
				$err = array(array(
					"message" => "App not found",
					"code" => "100003"
				));
				return $err;
			}
			die();
		}else{
			$err = array(array(
				"message" => "Item not found",
				"code" => "100002"
			));
			return $err;
		}
	}

}
