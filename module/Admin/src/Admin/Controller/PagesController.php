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

        $pages=$this->getPageTable()->JoinfetchAll();
        //echo "<pre>";print_r($pages);die;
        return new ViewModel(array(
            'pages' =>$this->getPageTable()->JoinfetchAll(),
        ));

    }

    public function addAction(){
        //Intializing page form
        $form = new PageForm();
        $form->get('submit')->setValue('Submit');
        
        //Getting post request and processing it
        $request = $this->getRequest();
        if ($request->isPost()) {//echo "<pre>";print_r($request->getPost());die;
            $page = new Page();
            $form->setInputFilter($page->getInputFilter());
            $form->setData($request->getPost());
             if ($form->isValid()) {
                $page->exchangeArray($form->getData());
                //echo "<pre>";print_r($page);die;
                $page_id=$this->getPagesTable()->SavePages($page->page_id);
                    $page->page_id=$page_id;
                $this->getPageTable()->savePage($page);

                // Redirect to list of albums
                $this->flashMessenger()->addSuccessMessage('Data Inserted Successfully!');
                return $this->redirect()->toRoute('pages');
            }
        }
        //Returning page form
        return array('form' => $form);
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('pages', array(
                'action' => 'add'
            ));
        }
        $page = $this->getPageTable()->getPage($id);
        $page_name= $this->getPagesTable()->getPageName($page);

        $form  = new PageForm();
        $form->bind($page);
        $form->get('submit')->setAttribute('value', 'Edit');
        $form->get('page_id')->setAttribute('value', $page_name);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($page->getInputFilter());
            $page_id=$page->page_id;
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->getPagesTable()->savePagesById($page_id,$this->getRequest()->getPost('page_id'));
                $this->getPageTable()->savepage($form->getData());
                //echo "<pre>";print_r($page);die;
                // Redirect to list of pages
                $this->flashMessenger()->addSuccessMessage('Data Update Successfully!');
                return $this->redirect()->toRoute('pages');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }
    
    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('pages');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');
            echo $del;die;
            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getPageTable()->deletePage($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('pages');
        }

        return array(
            'id'    => $id,
            'album' => $this->getPageTable()->getAlbum($id)
        );
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