<?php

/**
 * SessionController
 *
 * Allows to register new users
 */
class RegisterController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Sign Up/Sign In');
        parent::initialize();
    }

    /**
     * Action to register a new user
     */
    public function indexAction()
    {
        $form = new RegisterForm;

        if ($this->request->isPost()) {

            $name = $this->request->getPost('name', array('string', 'striptags'));
            $username = $this->request->getPost('username', 'alphanum');
            $email = $this->request->getPost('email', 'email');
            $password = $this->request->getPost('password');
            $repeatPassword = $this->request->getPost('repeatPassword');

            if ($password != $repeatPassword) {
                $this->flash->error('Passwords are diferent');
                return false;
            }

            $user = new Users();
            $user->username = $username;
            $user->password = sha1($password);
            $user->name = $name;
            $user->email = $email;
            $user->created_at = new Phalcon\Db\RawValue('now()');
            $user->active = 'N';
            if ($user->save() == false) {
                foreach ($user->getMessages() as $message) {
                    $this->flash->error((string) $message);
                }
            } else {
                $this->tag->setDefault('email', '');
                $this->tag->setDefault('password', '');
                $this->flash->success('Thanks for sign-up, you have a letter at your E-mail');
                
                $this->sendConfirm($user->email, $user->password);
                
                return $this->forward('session/index');
            }
        }
        
        $this->view->form = $form;
    }
    
    public function confirmAction($hash,$email){
        $user = Users::findFirst(array(
            "conditions" => "email = :emailval:",
            "bind"       => array('emailval' => $email)
        ));
        if (isset($user->email) && isset($user->created_at)){
            $userhash = $this->getHash($user->email, $user->password);
            if ($userhash == $hash){
                $user->active = 'Y';
                
                if ($user->save() == false) {
                    foreach ($user->getMessages() as $message) {
                        $this->flash->error((string) $message);
                    }
                } else {
                    $this->flash->success('Thanks for confirm, please log-in to start generating invoices');

                    return $this->forward('session/index');
                }
            }else{
                $this->flash->error('Bad confirm code '.$userhash.'!='.$hash);
            }
        }else{
            $this->flash->error('There is no user with such E-mail');
        }
        
        
    }
    
    public function rememberAction(){
        $form = new RememberForm;
        
        if ($this->request->isPost()) {

            $email = $this->request->getPost('email', 'email');
            $password = $this->request->getPost('password');
            $repeatPassword = $this->request->getPost('repeatPassword');

            if ($password != $repeatPassword) {
                $this->flash->error('Passwords are diferent');
                return false;
            }
            
            $user = Users::findFirst(array(
                "conditions" => "email = :emailval:",
                "bind"       => array('emailval' => $email)
            ));
            
            
            if ($user){
                $user->new_password = sha1($password);
                if ($user->save() == false) {
                    foreach ($user->getMessages() as $message) {
                        $this->flash->error((string) $message);
                    }
                } else {
                    $this->flash->success('We have sent you a letter to confirm changing password');
                    
                    $this->sendRemember($user->email, $user->password);
                    
                    return $this->forward('session/index');
                }
            }else{
                $this->flash->error('There is no user with such E-mail');
            }
        }
        
        $this->view->form = $form;
    }
    
    public function confirmrememberAction($hash,$email){
        $user = Users::findFirst(array(
            "conditions" => "email = :emailval:",
            "bind"       => array('emailval' => $email)
        ));
        if (isset($user->email) && isset($user->created_at)){
            $userhash = $this->getHash($user->email, $user->password);
            if ($userhash == $hash){
                $user->active = 'Y';
                $user->password = $user->new_password;
                
                if ($user->save() == false) {
                    foreach ($user->getMessages() as $message) {
                        $this->flash->error((string) $message);
                    }
                } else {
                    $this->flash->success('Thanks for confirm, please log-in using your E-mail and password');

                    return $this->forward('session/index');
                }
            }else{
                $this->flash->error('Bad confirm code '.$userhash.'!='.$hash);
            }
        }else{
            $this->flash->error('There is no user with such E-mail');
        }
        
        
    }
    
    private function getHash($email, $password){
        return md5( $email.' | '.$password . '|');
    }
    
    private function sendConfirm($email, $password){
        return $this->getDI()->getMail()->send(
            array($email => $email),
            'Please confirm your email',
            'confirm',
            array( 'confirmUrl' => '/register/confirm/'. $this->getHash($email, $password) .'/'.$email)
        );
    }
    
    private function sendRemember($email, $password){
        return $this->getDI()->getMail()->send(
            array($email => $email),
            'Please confirm your email',
            'remember',
            array( 'confirmUrl' => '/register/confirmremember/'. $this->getHash($email, $password) .'/'.$email)
        );
    }
}
