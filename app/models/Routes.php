<?php

class Routes extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $track_id;

    /**
     *
     * @var double
     */
    public $latitude;

    /**
     *
     * @var double
     */
    public $longitude;

    /**
     *
     * @var integer
     */
    public $date;

    /**
     *
     * @var string
     */
    public $key;
    

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'track_id' => 'track_id', 
            'latitude' => 'latitude', 
            'longitude' => 'longitude', 
            'date' => 'date', 
            'key' => 'key'
        );
    }

}
