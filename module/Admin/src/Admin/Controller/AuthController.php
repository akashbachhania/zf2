<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Admin\Form\LoginForm;

class AuthController extends AbstractActionController
{
	public function indexAction()
    {
    	//die(' harer');
    }

    public function loginAction()
    {
        //$db = $this->_getParam('db');

        $form = new LoginForm($_POST);
        echo $form->isValid();die;
        echo "<pre>";print_r($form);die;

        if ($loginForm->isValid()) {

            $adapter = new Zend\Auth\Adapter\DbTable(
                $db,
                'users',
                'username',
                'password',
                'MD5(CONCAT(?, password_salt))'
                );

            $adapter->setIdentity($loginForm->getValue('username'));
            $adapter->setCredential($loginForm->getValue('password'));

            $result = $auth->authenticate($adapter);

            if ($result->isValid()) {
                $this->_helper->FlashMessenger('Successful Login');
                $this->redirect('/');
                return;
            }

        }

        $this->view->loginForm = $loginForm;

    }

}