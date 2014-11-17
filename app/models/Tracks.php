<?php

class Tracks extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    public $bike_id;

    /**
     *
     * @var integer
     */
    public $user_id;

    /**
     *
     * @var integer
     */
    public $distance;

    /**
     *
     * @var integer
     */
    public $datestart;

    /**
     *
     * @var integer
     */
    public $dateend;
    
    public function initialize()
    {
        $this->belongsTo("bike_id", "Bikes", "id");
        
    }
    
    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'title' => 'title', 
            'bike_id' => 'bike_id', 
            'user_id' => 'user_id', 
            'distance' => 'distance', 
            'datestart' => 'datestart', 
            'dateend' => 'dateend'
        );
    }

}
