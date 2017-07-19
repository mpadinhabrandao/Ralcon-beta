<?php
namespace Models;
use \PhalconRest\Exceptions\HTTPException;

use \Phalcon\Validation;
use \Phalcon\Validation\Validator\Uniqueness;
use \Phalcon\Validation\Validator\InclusionIn;
use \Phalcon\Validation\Validator\PresenceOf;
use \Models\Installables;

class Dependencies extends \Phalcon\Mvc\Model{

	const DELETED = 1;
	const NOT_DELETED = 0;

	public function getSource(){
		return "dependencies";
	}

	public function initialize(){

		$this->addBehavior(
			new \Phalcon\Mvc\Model\Behavior\SoftDelete(array(
				'field' => 'deleted',
				'value' => Versions::DELETED
			)
		));

		$this->addBehavior(
			new \Phalcon\Mvc\Model\Behavior\Timestampable(array(
				'beforeCreate' => array(
					'field' => 'last_edit',
					'format' => 'Y-m-d H:i:s'
				),
				'beforeUpdate' => array(
					'field' => 'last_edit',
					'format' => 'Y-m-d H:i:s'
				)
			)
		));

		return;
	}
	public function validation()
    {
        $validator = new Validation();
        $domain = array();
        $list = Installables::find(array('columns' => 'namespace'));
        if($list->count()){
        	foreach($list->toArray() as $itm){
        		$domain[] = $itm['namespace'];
        	}
        }
        
        $domainV = array();
        $list = Versions::find(
		array('columns' => 'version', 'conditions' => "namespace = '{$this->namespace}'")
	);
        if($list->count()){
        	foreach($list->toArray() as $itm){
        		$domainV[] = $itm['version'];
        	}
        }
        
        $domainVD = array();
        $list = Versions::find(
		array('columns' => 'version', 'conditions' => "namespace = '{$this->namespace_d}'")
	);
        if($list->count()){
        	foreach($list->toArray() as $itm){
        		$domainVD[] = $itm['version'];
        	}
        }
        
        $validator->add(
            "namespace",
            new InclusionIn(
                [
            		"message" => "The namespace not found",
            		"code" => '100001',
                	"domain" => $domain
                ]
            )
        );
        $validator->add(
            "namespace_d",
            new InclusionIn(
                [
            		"message" => "The namespace not found",
            		"code" => '100001',
                	"domain" => $domain
                ]
            )
        );
        $validator->add(
            "version",
            new InclusionIn(
                [
            		"message" => "The version not found",
            		"code" => '100001',
                	"domain" => $domainV
                ]
            )
        );
        $validator->add(
            "version_d",
            new InclusionIn(
                [
            		"message" => "The version not found",
            		"code" => '100001',
                	"domain" => $domainVD
                ]
            )
        );


        return $this->validate($validator);
    }

}
