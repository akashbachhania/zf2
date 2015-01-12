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

    public function getPage($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
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
            
        $id = (int)$page->id;
        if ($id == 0) {
            $data = array(
                'content'  => $page->content,
                'slug'  => $slug,
                'page_id'  => $page->page_id,
            );
            $this->tableGateway->insert($data);
        } else {
            $data = array(
                'content'  => $page->content,
                'slug'  => $slug,
            );
            if ($this->getPage($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function JoinfetchAll()
    {
        $sqlSelect = $this->tableGateway->getSql()->select();
        $sqlSelect->columns(array('slug','id'));
        $sqlSelect->join('pages', 'pages.id = page_content.page_id', array('page_name'), 'inner');

        $statement = $this->tableGateway->getSql()->prepareStatementForSqlObject($sqlSelect);
        $resultSet = $statement->execute();
        return $resultSet;
    }

    public function deletePage($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}