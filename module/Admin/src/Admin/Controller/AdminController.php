<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Model\Admin;
use Admin\Model\User;
use Admin\Form\AdminForm;
use Admin\Form\UserForm;
use Admin\Form\LoginForm;

class AdminController extends AbstractActionController
{
    protected $userTable;

    
    public function indexAction()
    {
        
        $form = new LoginForm();
        $form->get('submit')->setValue('Login');
        
        $this->layout('layout/login_layout.phtml');
        $request = $this->getRequest();
        if ($request->isPost()) {
            
        }
        return array('form' => $form);
    }

    public function listAction(){
        //checking if user is login or not
        if (! $this->getServiceLocator()
                 ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin');
        }

        return new ViewModel(array(
            'user' => $this->getUserTable()->fetchAll(),
        ));
    }

    public function addAction()
    {
        //checking if user is login or not
        if (! $this->getServiceLocator()
                 ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin');
        }

        $form = new UserForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $user = new User();
            $user->name=$request->getPost('name');
            $user->address=$request->getPost('address');
            $user->status=$request->getPost('status');
            //$form->setInputFilter($user->getInputFilter());
            //$form->setData($request->getPost());

            //if ($form->isValid()) {die('validator');
            //    $user->exchangeArray($form->getData());
                $this->getUserTable()->saveUser($user);

                // Redirect to list of user
                return $this->redirect()->toRoute('admin',
                    array('controller'=>'AdminController',
                        'action' => 'list'));
               // return $this->redirect()->toRoute('admin/list');
            //}
        }
        return array('form' => $form);
    }

    public function editAction()
    {
        //checking if user is login or not
        if (! $this->getServiceLocator()
                 ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin');
        }

        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('admin', array(
                'action' => 'add'
            ));
        }
        $admin = $this->getUserTable()->getUser($id);

        $form  = new UserForm();
        $form->bind($admin);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            //$form->setInputFilter($admin->getInputFilter());
           // $form->setData($request->getPost());
            //echo "<pre>";print_r($form->getData());die(' controller');
           // if ($form->isValid()) {
                $user = new User();
                $user->name=$request->getPost('name');
                $user->address=$request->getPost('address');
                $user->status=$request->getPost('status');
                $user->id=$request->getPost('id');
                $this->getUserTable()->saveUser($user);

                // Redirect to list of admins
                // Redirect to list of user
                return $this->redirect()->toRoute('admin',
                    array('controller'=>'AdminController',
                        'action' => 'list'));
           // }
        }
       
        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
        //checking if user is login or not
        if (! $this->getServiceLocator()
                 ->get('AuthService')->hasIdentity()){
            return $this->redirect()->toRoute('admin');
        }

        $id = (int) $this->params()->fromRoute('id', 0);

        if (!$id) {
            return $this->redirect()->toRoute('admin');
        }
        else{
            // Redirect to list of admins
            $this->getUserTable()->deleteUser($id);
            return $this->redirect()->toRoute('admin',
                array('controller'=>'AdminController',
                    'action' => 'list'));
        }
       
        
    }

    public function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('Admin\Model\UserTable');
        }
        return $this->userTable;
    }
}