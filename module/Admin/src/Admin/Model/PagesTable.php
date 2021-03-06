<?php
// module/Album/src/Album/Model/AlbumTable.php:
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;

class PagesTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getPageName(Page $page)
    {
       $page_id = (int) $page->page_id;
        $rowset = $this->tableGateway->select(array('id' => $page_id));
        $row = $rowset->current();
        //print_r($row->page_name);die;
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row->page_name;
    }

    public function savePages($page_name)
    {
        $data = array(
            'page_name'  => $page_name,
        );
        $this->tableGateway->insert($data);
        $id=$this->tableGateway->getLastInsertValue();
        return $id;
    }

    public function savePagesById($page_id,$page_name){
        $data = array(
            'page_name'  => $page_name,
        );
        $this->tableGateway->update($data, array('id' => $page_id));
    }
}