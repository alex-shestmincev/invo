<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
//use Phalcon\Validation\Validator\Numericality;
use Phalcon\Mvc\Model\Validator\Numericality;

class TracksEditForm extends Form
{

    /**
     * Initialize the products form
     */
    public function initialize($entity = null, $options = array())
    {

        if (isset($options['edit'])) {
//            $element = new Hi("id");
//            $this->add($element->setLabel("Id"));
//        } else {
            $this->add(new Hidden("id"));
        }

        $name = new Text("title");
        $name->setLabel("Title");
        $name->setFilters(array('striptags', 'string'));
        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'Title is required'
            ))
        ));
        $this->add($name);
        
        $bike = new Select('bike_id', Bikes::find() , array(
            'using'      => array('id', 'title'),
            'useEmpty'   => true,
            'emptyText'  => 'All',
            'emptyValue' => '0'
        ));
        $bike->setLabel('Bike');
        $this->add($bike);
        
        $user = new Select('user_id', Users::find() , array(
            'using'      => array('id', 'email'),
            'useEmpty'   => true,
            'emptyText'  => 'All',
            'emptyValue' => '0'
        ));
        $user->setLabel('User');
        $this->add($user);
        
        


        $distance = new Numeric("distance");
        $distance->setLabel("Distance");
        $distance->setFilters(array('int'));
        $distance->addValidators(array(
            new PresenceOf(array(
                'message' => 'Distance is required'
            )),
//            new Numericality(array(
//                'message' => 'Distance is required'
//            ))
        ));
        $this->add($distance);
    }
}