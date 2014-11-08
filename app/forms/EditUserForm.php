<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Radio;

use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;

class EditUserForm extends Form
{

    public function initialize($entity = null, $options = null)
    {   
        
        $this->add(new Hidden("id"));
        
        // Name
        $name = new Text('name');
        $name->setLabel('Your Full Name');
        $name->setFilters(array('striptags', 'string'));
//        $name->addValidators(array(
//            new PresenceOf(array(
//                'message' => 'Name is required'
//            ))
//        ));
        $this->add($name);
        
        
        // Name
        $name = new Text('username');
        $name->setLabel('Username');
        $name->setFilters(array('striptags', 'string'));
        //$name->setFilters(array('alpha'));
        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'Please enter your desired user name'
            ))
        ));
        $this->add($name);

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
        
        $type = new Select('active', Users::userActivateOptions() , array());
        $type->setLabel('Active');
        $this->add($type);
        
        $type = new Select('level', Users::userLevelOptions() , array());
        $type->setLabel('User level');
        $this->add($type);
        
    }
}