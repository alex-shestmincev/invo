<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class UserController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Manage your Invoices');
        parent::initialize();
    }
    
    public function indexAction()
    {   
        
        $numberPage = 1;
		if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $query = Criteria::fromInput($this->di, "Users",  $this->clearEmptyData($post,false) );
            $this->persistent->searchParams = $query->getParams();
		} else {
			$numberPage = $this->request->getQuery("page", "int");
		}

		$parameters = array();
		if ($this->persistent->searchParams) {
			$parameters = $this->persistent->searchParams;
		}
        //print_r($parameters);
        
        $users = Users::find($parameters);
        $paginator = new Paginator(array(
			"data"  => $users,
			"limit" => 10,
			"page"  => $numberPage
		));

		$this->view->page = $paginator->getPaginate();
		$this->view->users = $users;
        $this->view->user_roles = Users::userLevelOptions();
        
        $this->view->form = new UsersForm;
    }
    
    public function editAction($userID){
        
        if (!$this->request->isPost()) {

			$user = Users::findFirstById($userID);
			if (!$user) {
				$this->flash->error("User was not found");
				return $this->forward("user/index");
			}

			$this->view->form = new EditUserForm($user, array('edit' => true));
		}
    }
    
    public function saveAction()
	{
		if (!$this->request->isPost()) {
			return $this->forward("user/index");
		}

		$id = $this->request->getPost("id", "int");

		$user = Users::findFirstById($id);
		if (!$user) {
			$this->flash->error("User does not exist");
			return $this->forward("user/index");
		}

        $data = $this->request->getPost();
        $form = new EditUserForm();
        //var_dump($data);
        
        if (!$form->isValid($data, $user)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward('user/edit/' . $id);
        }
        
        if ($user->save() == false) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward('user/edit/' . $id);
        }

        $form->clear();

		$this->flash->success("User was updated successfully");
		return $this->forward("user/index");
	}
    
    /**
	 * Deletes a user
	 *
	 * @param string $id
	 */
	public function deleteAction($id)
	{   
        $users = Users::findFirstById($id);
        if (!$users) {
            $this->flash->error("User was not found");
            return $this->forward("user/index");
        }
        
        if (!$this->request->isPost()){
            
            $this->view->user = $users;
            
        }elseif($this->request->getPost('confirm') == 'Yes'){
            
            if (!$users->delete()) {
                foreach ($users->getMessages() as $message) {
                    $this->flash->error($message);
                }
                return $this->forward("user/index");
            }

            $this->flash->success("User was deleted");
            return $this->forward("user/index");
        }else{
            $this->flash->error("Error during delete user");
            return $this->forward("user/index");
        }
	}

}

