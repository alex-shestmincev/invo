<?php

use Phalcon\Acl;
use Phalcon\Acl\Role;
use Phalcon\Acl\Resource;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Adapter\Memory as AclList;

/**
 * SecurityPlugin
 *
 * This is the security plugin which controls that users only have access to the modules they're assigned to
 */
class SecurityPlugin extends Plugin
{
    static $instance;
    
    private function  __construct(){
        
    }
    
    public static function getInstance(){
        if (self::$instance == false){
            self::$instance = new SecurityPlugin();
        }
        
        return self::$instance;
    }
    
	/**
	 * Returns an existing or new access control list
	 *
	 * @returns AclList
	 */
	public function getAcl()
	{

		//throw new \Exception("something");

		if (!isset($this->persistent->acl)) {

			$acl = new AclList();

			$acl->setDefaultAction(Acl::DENY);

			//Register roles
			$roles = array(
                Users::LEVEL_GUESTS => new Role('Guests'),
				Users::LEVEL_USERS  => new Role('Users'),
				Users::LEVEL_MANAGERS  => new Role('Managers'),
                Users::LEVEL_ADMINS    => new Role('Admins'),
			);
			foreach ($roles as $role) {
				$acl->addRole($role);
			}
            
            $Resources = array(
                
                // Guest(Public) area resources
                Users::LEVEL_GUESTS => array(
                    'index'      => array('index'),
                    'about'      => array('index'),
                    'register'   => array('index'),
                    'errors'     => array('show404', 'show500'),
                    'session'    => array('index', 'register', 'start', 'end'),
                    'contact'    => array('index', 'send'),
                ),
                
                //User area resources
                Users::LEVEL_USERS => array(
                    'companies'    => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete'),
                    'products'     => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete'),
                    'producttypes' => array('index', 'search', 'new', 'edit', 'save', 'create', 'delete'),
                    'invoices'     => array('index', 'profile'),
                ),
                
                //Manager area resources
                Users::LEVEL_MANAGERS => array(
                    'bikes'        => array('index','edit','new','save','create','delete'),
                    'images'       => array('load','delete'),
                    'tracks'       => array('index','edit','new','save','create','delete','finish'),
                ),
                
                //Admin area resources
                Users::LEVEL_ADMINS => array(
                    'user'       => array('index','edit','save'/*,'delete'*/),
                ),
                
            );
            
            // Add resources
            foreach($Resources as $level => $_resources){
                foreach ($_resources as $resource => $actions) {
                    $acl->addResource(new Resource($resource), $actions);
                }
            }
            
            foreach ($roles as $role_level=>$role) {
				foreach ($Resources as $resource_level => $values) { 
                    if ($role_level >= $resource_level){
                        foreach ($values as $resource => $actions) {
                            if ($resource_level == Users::LEVEL_GUESTS){
                                $acl->allow($role->getName(), $resource, '*');
                            }else{
                                foreach ($actions as $action){ //echo $role->getName() . " ".$resource." ".$action."<br>";
                                    $acl->allow($role->getName(), $resource, $action);
                                }
                            }
                       }
                    }
				}
			}
            
			//The acl is stored in session, APC would be useful here too
			$this->persistent->acl = $acl;
		}

		return $this->persistent->acl;
	}

	/**
	 * This action is executed before execute any action in the application
	 *
	 * @param Event $event
	 * @param Dispatcher $dispatcher
	 */
	public function beforeDispatch(Event $event, Dispatcher $dispatcher)
	{

		$role = $this->getUserRole();
        
		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();

		$acl = $this->getAcl();
        //echo "<pre>";print_r($acl);echo "</pre>";
		$allowed = $acl->isAllowed($role, $controller, $action);
		if ($allowed != Acl::ALLOW) {
			$dispatcher->forward(array(
				'controller' => 'errors',
				'action'     => 'show401'
			));
			return false;
		}
	}
    
    public function getUserRole(){
        $auth = $this->session->get('auth');
        $userLevelOptions = Users::userLevelOptions(); 
        
		if (!$auth || !isset($auth['level']) || $auth['level'] == Users::LEVEL_GUESTS){
			$role = $userLevelOptions[Users::LEVEL_GUESTS];
		} elseif ( $auth['level'] == Users::LEVEL_USERS) {
			$role = $userLevelOptions[Users::LEVEL_USERS];
		} elseif ( $auth['level'] == Users::LEVEL_MANAGERS) {
			$role = $userLevelOptions[Users::LEVEL_MANAGERS];
		} elseif ( $auth['level'] == Users::LEVEL_ADMINS) {
			$role = $userLevelOptions[Users::LEVEL_ADMINS];
        }
        
        return $role;
    }
}
