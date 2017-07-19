<?php
namespace PhalconRest\Controllers;
use \PhalconRest\Exceptions\HTTPException;
use \Phalcon\Http\Request;

class RESTBaseController extends \PhalconRest\Controllers\RESTController{

	protected $primaryKey = 'id';
	
	public function get(){
		$list = array();
		
		$params = $this->respondParams($this->model);
		$tmp = $this->model->find($params);
		if( $tmp->count() ){
			$list = $tmp->toArray();	
		}
		return $list;
	}

	public function getOne($id){
		
		$array['conditions'] = "{$this->primaryKey} = :{$this->primaryKey}:";
		$array['bind'][$this->primaryKey] = $id;
		
		$itm = $this->model->findFirst($array);
		if( $itm ){
			return $itm->toArray();
		}else{
			$err = array(array(
				"message" => "Item not found",
				"code" => "100002"
			));
			return $err;
		}
	}
	

	protected function save($model){
		
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

	public function post(){
		return $this->save($this->model);
	}

	public function put($id){
		
		$array['conditions'] = "{$this->primaryKey} = :{$this->primaryKey}:";
		$array['bind'][$this->primaryKey] = $id;
		
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

	public function delete($id){
		
		$array['conditions'] = "{$this->primaryKey} = :{$this->primaryKey}:";
		$array['bind'][$this->primaryKey] = $id;
		
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

	public function respondParams($model){
		$array = array();
		
		$attributes = $model->getModelsMetaData()->getAttributes($model);
		
		if( !empty($this->partialFields) ){
			$array['columns'] = implode(
						', ', 
						array_intersect($attributes, $this->partialFields)
						);
		}
		if( isset($this->limit) && $this->limit >0 )
			$array['limit'] = $this->limit;
			
		if( isset($this->offset) && $this->offset >0 )
			$array['offset'] = $this->offset;
		if( count($this->searchFields) ) {
			foreach ($this->searchFields as $field => $value) {
				$array['conditions'] =	( empty($array['conditions']) ) ? 
							"$field = :$field:" : 
							"{$array['conditions']} AND $field = :$field:";
				$array['bind'][$field] = $value;
			}
		}
		if( !empty($this->orders) ){
			$array['order'] = implode($this->orders);
		}
		return $array;		
	}
}
