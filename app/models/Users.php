<?php

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Email as EmailValidator;
use Phalcon\Mvc\Model\Validator\Uniqueness as UniquenessValidator;

class Users extends \Phalcon\Mvc\Model
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
    public $username;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $new_password;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $active;
    
    /**
     *
     * @var integer
     */
    public $level;
    
    const LEVEL_GUESTS = 1;
    const LEVEL_USERS = 2;
    const LEVEL_MANAGERS = 3;
    const LEVEL_ADMINS = 4;
    
    const ACTIVATE_YES = 'Y';
    const ACTIVATE_NO = 'N';
    
    static function userLevelOptions(){
        $options = array(
            self::LEVEL_GUESTS => 'Guests',
            self::LEVEL_USERS => 'Users',
            self::LEVEL_MANAGERS => 'Managers',
            self::LEVEL_ADMINS => 'Admins',
        );
        
        return $options;
    }
    
    static function userActivateOptions(){
        $options = array(
            self::ACTIVATE_YES => 'Active',
            self::ACTIVATE_NO => 'Disable',
        );
        
        return $options;
    }
    
    public function validation()
    {
        $this->validate(new EmailValidator(array(
            'field' => 'email'
        )));
        $this->validate(new UniquenessValidator(array(
            'field' => 'email',
            'message' => 'Sorry, The email was registered by another user'
        )));
        $this->validate(new UniquenessValidator(array(
            'field' => 'username',
            'message' => 'Sorry, That username is already taken'
        )));
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    /**
     * Independent Column Mapping.
     */
    public function columnMap()
    {
        return array(
            'id' => 'id', 
            'username' => 'username', 
            'password' => 'password', 
            'new_password' => 'new_password', 
            'name' => 'name', 
            'email' => 'email', 
            'created_at' => 'created_at', 
            'active' => 'active',
            'level' => 'level'
        );
    }

}
