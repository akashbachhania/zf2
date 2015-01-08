<?php
// module/Album/src/Album/Model/AlbumTable.php:
namespace Admin\Model;

use Zend\Db\TableGateway\TableGateway;

class PageTable
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

    public function getPage($page_id)
    {
        $page_id = (int) $page_id;
        $rowset = $this->tableGateway->select(array('page_id' => $page_id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $page_id");
        }
        return $row;
    }

    public function savePage(Page $page)
    {//echo "<pre>";print_r($page->id);die;
        //checking slug
        if(empty($page->slug)){
            $slug=$page->page_name;
        }
        else{
            $slug=$page->slug;
        }
            
        $data = array(
            'content'  => $page->content,
            'slug'  => $slug,
            'page_id'  => $page->page_id,
        );

        $id = (int)$page->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getPage($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteAlbum($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}