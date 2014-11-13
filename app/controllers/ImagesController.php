<?php

use Phalcon\Image\Adapter\GD,
    Phalcon\Mvc\View;

class ImagesController extends \Phalcon\Mvc\Controller
{
    
    static $path_big = "files/big/";
    static $path_min = "files/min/";
    
    static $min_width = 100;
    static $min_height = 100;
    
    static $big_width = 800;
    static $big_height = 800;
    
    static $alowedTypes = array(
        'image/jpeg' => '.jpg'
    );
    
    const TYPE_BIKES = 1;
    
    
    public function indexAction()
    {

    }
    
    private function setTypeImage($type){
        if ( in_array($type, array_keys(self::$alowedTypes) ) ){
            return self::$alowedTypes[$type];
        }else{
            die('Bad image type');
        }
    }
    
    public function loadAction($type,$type_id){
        
        $type = (int) $type;
        $type_id = (int) $type_id;
        
        #check if there is any file
        if($this->request->hasFiles() == true){
            $uploads = $this->request->getUploadedFiles();
            $isUploaded = false;
            #do a loop to handle each file individually
            foreach($uploads as $upload){
                
                try{
                    $name = rand(1000,999999). $this->setTypeImage($upload->getType());
                    $min_path = self::$path_min .$name;
                    $big_path = self::$path_big .$name;
                    
                    $image = new GD($upload->getTempName());
                    $image->resize(self::$min_width, self::$min_height)->save($min_path , 90);
                    
                    $image = new GD($upload->getTempName());
                    $image->resize(self::$big_width, self::$big_height)->save($big_path , 90);
                    
                    $model = new Images();
                    $model->type = $type;
                    $model->type_id = $type_id;
                    $model->link_min = $min_path;
                    $model->link_big = $big_path;
                    
                    if ( !$model->save()){
                        throw new Exception('Error for save image');
                    }
                    
                }catch(Exception $e){
                    die('Error for save image');
                }
               
            }
        }else{
            #if no files were sent, throw a message warning user
            die("You must choose at least one file to send. Please try again.");
        }
        
        $this->view->disableLevel(array(
            View::LEVEL_MAIN_LAYOUT => true
        ));
        
        $this->view->image = $model;
        
    }
    
    public function deleteAction(){
        $imageID = (int) $this->request->getPost('id');
        
        $image = Images::findFirstById($imageID);
        
        $status = 0;
        $error_message = '';
        
        if ($image){
            if ($image->delete()){
                $status = 1;
            }else{
                $error_message = 'Error while deleting';
            }
        }else{
            $error_message = "Can\'t find image";
        }
        
        $data = array('status' => $status, 'error' => $error_message);
        
        echo json_encode($data);
        
        $this->view->disableLevel(array(
            View::LEVEL_LAYOUT => true,
            View::LEVEL_MAIN_LAYOUT => true
        ));
    }
}

