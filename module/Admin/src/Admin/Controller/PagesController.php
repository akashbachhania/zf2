<?php
//module/Admin/src/Admin/Controller/PagesController.php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Admin\Form\PageForm;
use Admin\Form\AddPageForm;
use Admin\Model\Page;
use Admin\Model\Pages;

class PagesController extends AbstractActionController{

	protected $pageTable;
    protected $pagesTable;
	//protected $pagesTable;
	public function indexAction(){
        //Dynamic page list from pages table
        $options=$this->getPagesTable()->fetchAll();
        foreach ($options as $option){
            $drop[$option->id]=$option->page_name;
        }
        //Intializing page form
		$form = new PageForm();
        $form->get('page_id')->setValueOptions($drop);
		$form->get('submit')->setValue('Submit');
		
		//Getting post request and processing it
		$request = $this->getRequest();
        if ($request->isPost()) {
        	$page = new Page();
        	$form->setInputFilter($page->getInputFilter());
        	$form->setData($request->getPost());
        	 if ($form->isValid()) {
                $page->exchangeArray($form->getData());
                $page_name=$this->getPagesTable()->getPageName($page);
                $page->page_name=$page_name;
                $this->getPageTable()->savePage($page);

                // Redirect to list of albums
                return $this->redirect()->toRoute('pages');
            }
        }
		//Returning page form
		return array('form' => $form);
    }

    public function addAction(){
        $form = new AddPageForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $pages = new Pages();
            $form->setInputFilter($pages->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $pages->exchangeArray($form->getData());
                $this->getpagesTable()->savePages($pages);

                // Redirect to list of albums
                return $this->redirect()->toRoute('pages');
            }
        }
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

    public function getPagesTable()
    {
        if (!$this->pagesTable) {
            $sm = $this->getServiceLocator();
            $this->pagesTable = $sm->get('Admin\Model\PagesTable');
        }
        return $this->pagesTable;
    }
}