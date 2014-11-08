<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
//use Phalcon\Validation\Validator\Numericality;
use Phalcon\Mvc\Model\Validator\Numericality;

class UsersForm extends Form
{

    /**
     * Initialize the products form
     */
    public function initialize($entity = null, $options = array())
    {
        
        // Email
        $email = new Text('email');
        $email->setLabel('E-Mail');
        $email->setFilters('email');
        $email->addValidators(array(
            new PresenceOf(array(
                'message' => 'E-mail is required'
            )),
            new Email(array(
                'message' => 'E-mail is not valid'
            ))
        ));
        $this->add($email);
        
        $type = new Select('level', Users::userLevelOptions() , array(
            //'using'      => array('id', 'name'),
            'useEmpty'   => true,
            'emptyText'  => 'All',
            'emptyValue' => '0'
        ));
        $type->setLabel('User level');
        $this->add($type);

    }
}