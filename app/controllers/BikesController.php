<?php


use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class BikesController extends ControllerBase
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
			//$query = Criteria::fromInput($this->di, "Products", $this->request->getPost());
			//$this->persistent->searchParams = $query->getParams();
		} else {
			$numberPage = $this->request->getQuery("page", "int");
		}
        
        $bikes = Bikes::find();
        
       
        $images = array();
        
        foreach ($bikes as $key => $bike){
            if ($bike->images->toArray()){
                $images[$bike->id] = $bike->images->getFirst()->link_min;
            }
        }
        
        $paginator = new Paginator(array(
			"data"  => $bikes,
			"limit" => 10,
			"page"  => $numberPage
		));

		$this->view->page = $paginator->getPaginate();
		$this->view->bikes = $bikes;
        $this->view->images = $images;
        
    }
    
    public function newAction(){
        
        $this->view->form = new BikesEditForm();
		
    }
    
    
    public function editAction($Id){
        
        if (!$this->request->isPost()) {

			$bike = Bikes::findFirstById($Id);
			if (!$bike) {
				$this->flash->error("Bike was not found");
				return $this->forward("bikes/index");
			}
            
            $this->view->bike = $bike;
			$this->view->form = new BikesEditForm($bike, array('edit' => true));
            $this->view->images = $bike->images;
		}
    }
    
    /**
	 * Creates a new product
	 */
	public function createAction()
	{
		if (!$this->request->isPost()) {
			return $this->forward("bikes/index");
		}

		$form = new BikesEditForm;
        $bikes = new Bikes();

        $data = $this->request->getPost();
        if (!$form->isValid($data, $bikes)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward('bikes/new');
        }

        if ($bikes->save() == false) {
            foreach ($bikes->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward('bikes/new');
        }

        $form->clear();

		$this->flash->success("Bike was created successfully");
		return $this->forward("bikes/index");
	}
    
    /**
	 * Saves current product in screen
	 *
	 * @param string $id
	 */
	public function saveAction()
	{
		if (!$this->request->isPost()) {
			return $this->forward("bikes/index");
		}

		$id = $this->request->getPost("id", "int");

		$bike = Bikes::findFirstById($id);
		if (!$bike) {
			$this->flash->error("Bike does not exist");
			return $this->forward("bikes/index");
		}

		$form = new BikesEditForm();
		$this->view->form = $form;

        $data = $this->request->getPost();

        //var_dump($data);

        if (!$form->isValid($data, $bike)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward('bikes/edit/' . $id);
        }

        if ($bike->save() == false) {
            foreach ($bike->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward('bikes/edit/' . $id);
        }

        $form->clear();

		$this->flash->success("Bike was updated successfully");
		return $this->forward("bikes/index");
	}
    
    /**
	 * Deletes a bike
	 *
	 * @param string $id
	 */
	public function deleteAction($id)
	{   
        $bikes = Bikes::findFirstById($id);
        if (!$bikes) {
            $this->flash->error("Bike was not found");
            return $this->forward("bikes/index");
        }
        
        if (!$this->request->isPost()){
            
            $this->view->bike = $bikes;
            
        }elseif($this->request->getPost('confirm') == 'Yes'){
            
            if (!$bikes->delete()) {
                foreach ($users->getMessages() as $message) {
                    $this->flash->error($message);
                }
                return $this->forward("bikes/index");
            }

            $this->flash->success("Bike was deleted");
            return $this->forward("bikes/index");
        }else{
            $this->flash->error("Error during delete bike");
            return $this->forward("bikes/index");
        }
	}

}

