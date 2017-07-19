<?php
namespace Models;
use \PhalconRest\Exceptions\HTTPException;

use \Phalcon\Validation;
use \Phalcon\Validation\Validator\Uniqueness;
use \Phalcon\Validation\Validator\InclusionIn;
use \Phalcon\Validation\Validator\PresenceOf;
use \Models\Installables;

class Versions extends \Phalcon\Mvc\Model{

	const DELETED = 1;
	const NOT_DELETED = 0;

	public function getSource(){
		return "versions";
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
		    "version",
		    new PresenceOf(
		        [
		            "message" => "The version is required",
		        ]
		    )
		);

        return $this->validate($validator);
    }

}
