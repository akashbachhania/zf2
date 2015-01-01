<?php
//module/SanAuth/src/SanAuth/Controller/AuthController.php
namespace Album\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\View\Model\ViewModel;
use Admin\Form\LoginForm;
 
use Album\Model\User;
 
class AuthController extends AbstractActionController
{
    protected $form;
    protected $storage;
    protected $authservice;
     
    public function getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()
                                      ->get('AuthService');
        }
         
        return $this->authservice;
    }
     
    public function getSessionStorage()
    {
        if (! $this->storage) {
            $this->storage = $this->getServiceLocator()
                                  ->get('Album\Model\MyAuthStorage');
        }
         
        return $this->storage;
    }
     
    public function getForm()
    {
        if (! $this->form) {
        	$this->form = new LoginForm();
            //$user       = new User();
            //$user = new LoginForm();
            //echo "<pre>";print_r($user);die(' aja');
            //$builder    = new AnnotationBuilder();
            //$this->form = $builder->createForm($user);
           // echo "<pre>";print_r($this->form);die(' hre');
        }
         
        return $this->form;
    }
     
    public function loginAction()
    {
        //if already login, redirect to success page 
        if ($this->getAuthService()->hasIdentity()){
            return $this->redirect()->toRoute('success');
        }
                 
        $form       = $this->getForm();
         
        return array(
            'form'      => $form,
            'messages'  => $this->flashmessenger()->getMessages()
        );
    }
     
    public function authenticateAction()
    {
        $form       = $this->getForm();
        $redirect = 'admin';
         
        $request = $this->getRequest();
        if ($request->isPost()){
            $form->setData($request->getPost());
            if ($form->isValid()){
                //check authentication...
                $this->getAuthService()->getAdapter()
                                       ->setIdentity($request->getPost('username'))
                                       ->setCredential($request->getPost('password'));
                                        
                $result = $this->getAuthService()->authenticate();
                foreach($result->getMessages() as $message)
                {
                    //save message temporary into flashmessenger
                    $this->flashmessenger()->addMessage($message);
                }
                if ($result->isValid()) {
                   // $redirect = 'list';
                    //check if it has rememberMe :
                    if ($request->getPost('rememberme') == 1 ) {
                        $this->getSessionStorage()
                             ->setRememberMe(1);
                        //set storage again 
                        $this->getAuthService()->setStorage($this->getSessionStorage());
                    }
                    $this->getAuthService()->getStorage()->write($request->getPost('username'));
                    return $this->redirect()->toRoute('admin',
                        array('controller'=>'AdminController',
                              'action' => 'list'));
                }
            }
        }
        return $this->redirect()->toRoute($redirect);
    }
     
    public function logoutAction()
    {
        $this->getSessionStorage()->forgetMe();
        $this->getAuthService()->clearIdentity();
         
        $this->flashmessenger()->addMessage("You've been logged out");
        return $this->redirect()->toRoute('login');
    }
}