<?php
use Phalcon\Paginator\Adapter\Model as Paginator;

class TracksController extends ControllerBase
{

    public function indexAction()
    {
        $numberPage = 1;
		if ($this->request->isPost()) {
			//$query = Criteria::fromInput($this->di, "Products", $this->request->getPost());
			//$this->persistent->searchParams = $query->getParams();
		} else {
			$numberPage = $this->request->getQuery("page", "int");
		}
        
        $tracks = Tracks::find();
        
               
        $paginator = new Paginator(array(
			"data"  => $tracks,
			"limit" => 10,
			"page"  => $numberPage
		));

		$this->view->page = $paginator->getPaginate();
		$this->view->tracks = $tracks;
        
    }
    
    public function newAction(){
        $this->view->form = new TracksEditForm();
    }
    
    public function editAction($Id){
        
        if (!$this->request->isPost()) {

			$track = Tracks::findFirstById($Id);
			if (!$track) {
				$this->flash->error("Track was not found");
				return $this->forward("track/index");
			}
            
            $this->view->track = $track;
			$this->view->form = new TracksEditForm($track, array('edit' => true));
            
		}
    }
    
    /**
	 * Creates a new track
	 */
	public function createAction()
	{
		if (!$this->request->isPost()) {
			return $this->forward("tracks/index");
		}

		$form = new TracksEditForm;
        $tracks = new Tracks();
        
        $tracks -> datestart = time();
        $tracks -> dateend = time();

        $data = $this->request->getPost();
        if (!$form->isValid($data, $tracks)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward('tracks/new');
        }

        if ($tracks->save() == false) {
            foreach ($tracks->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward('bikes/new');
        }

        $form->clear();

		$this->flash->success("Track was created successfully");
		return $this->forward("tracks/index");
	}
    
    /**
	 * Saves current product in screen
	 *
	 * @param string $id
	 */
	public function saveAction()
	{
		if (!$this->request->isPost()) {
			return $this->forward("tracks/index");
		}

		$id = $this->request->getPost("id", "int");

		$track = Tracks::findFirstById($id);
		if (!$track) {
			$this->flash->error("Track does not exist");
			return $this->forward("tracks/index");
		}

		$form = new TracksEditForm();
		$this->view->form = $form;

        $data = $this->request->getPost();

        //var_dump($data);

        if (!$form->isValid($data, $track)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward('tracks/edit/' . $id);
        }

        if ($track->save() == false) {
            foreach ($track->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->forward('tracks/edit/' . $id);
        }

        $form->clear();

		$this->flash->success("Track was updated successfully");
		return $this->forward("tracks/index");
	}

}

