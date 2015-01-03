<?php
//module/Admin/src/Admin/Controller/PagesController.php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Form\PageForm;
use Admin\Model\Page;

class PagesController extends AbstractActionController{

	protected $pageTable;
	//protected $pagesTable;
	public function indexAction(){
		//Intializing page form
		$form = new PageForm();
		$form->get('submit')->setValue('Submit');
		
		//Getting post request and processing it
		$request = $this->getRequest();
        if ($request->isPost()) {
        	$page = new Page();
        	$form->setInputFilter($page->getInputFilter());
        	$form->setData($request->getPost());
        	 if ($form->isValid()) {
                $page->exchangeArray($form->getData());
                $this->getPageTable()->savePage($page);

                // Redirect to list of albums
                return $this->redirect()->toRoute('pages');
            }
        }
		
		//Returning page form
		return array('form' => $form);
    }

    public function getPageTable()
    {
        if (!$this->pageTable) {
            $sm = $this->getServiceLocator();
            $this->pageTable = $sm->get('Admin\Model\PageTable');
        }
        return $this->pageTable;
    }
}