<?php
//module/Admin/src/Admin/Controller/PagesController.php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Form\PageForm;

class PagesController extends AbstractActionController{

	//protected $pagesTable;
	public function indexAction(){
		//Intializing page form
		$form = new PageForm();
		$form->get('submit')->setValue('Submit');

		//Getting post request and processing it
		$request = $this->getRequest();
        if ($request->isPost()) {
            echo $request->getPost('content');die(' jrwe');
        }
		
		//Returning page form
		return array('form' => $form);
    }
}