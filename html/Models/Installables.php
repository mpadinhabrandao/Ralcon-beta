<?php
namespace Models;
use \PhalconRest\Exceptions\HTTPException;

use \Phalcon\Validation;
use \Phalcon\Validation\Validator\Uniqueness;
use \Phalcon\Validation\Validator\InclusionIn;

class Installables extends \Phalcon\Mvc\Model{

	const DELETED = 1;
	const NOT_DELETED = 0;

	public function getSource(){
		return "installables";
	}

	
	public function initialize(){

		$this->addBehavior(
			new \Phalcon\Mvc\Model\Behavior\SoftDelete(array(
				'field' => 'deleted',
				'value' => Installables::DELETED
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

        $validator->add(
            "type",
            new InclusionIn(
                [
            		"message" => "The type not found",
            		"code" => '100001',
                	"domain" => [
                        	"App",
                        	"Plugin",
							"Template",
							"Vendor"
                    	]
                ]
            )
        );

        $validator->add(
            "namespace",
            new Uniqueness(
                [
                    "message" => "The namespace must be unique",
                ]
            )
        );

        return $this->validate($validator);
    }

}
