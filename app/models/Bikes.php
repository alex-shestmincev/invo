<?php

class Bikes extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var integer
     */
    public $distance;
    
    public $first_image_link;
    
    public function initialize()
    {
        $this->hasMany("id", "Images", "type_id");
    }
    
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'title' => 'title', 
            'description' => 'description', 
            'distance' => 'distance'
        );
    }

}
