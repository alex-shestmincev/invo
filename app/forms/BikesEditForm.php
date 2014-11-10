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

class BikesEditForm extends Form
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
        
        $description = new Text("description");
        $description->setLabel("Description");
        $description->setFilters(array('striptags', 'string'));
        $description->addValidators(array(
            new PresenceOf(array(
                'message' => 'Description is required'
            ))
        ));
        $this->add($description);


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