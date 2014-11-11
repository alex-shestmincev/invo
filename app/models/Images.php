<?php

class Images extends \Phalcon\Mvc\Model
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
    public $type;

    /**
     *
     * @var integer
     */
    public $type_id;

    /**
     *
     * @var string
     */
    public $link_min;

    /**
     *
     * @var string
     */
    public $link_big;
    

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'type' => 'type', 
            'type_id' => 'type_id', 
            'link_min' => 'link_min', 
            'link_big' => 'link_big'
        );
    }

}
