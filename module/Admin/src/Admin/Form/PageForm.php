<?php
// module/Admin/src/Admin/Form/AdminForm.php:
namespace Admin\Form;

use Zend\Form\Form;

class PageForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('admin');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'label',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control top',
                'placeholder' => 'Label',
            ),
        ));

        $this->add(array(
            'name' => 'slug',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control top',
                'placeholder' => 'Slug',
            ),
        ));

        $this->add(array(
            'name' => 'content',
            'attributes' => array(
                'id'  => 'content1234',
                'type'  => 'textarea',
                'class' => 'form-control bottom',
                'placeholder' => 'content',
            ),
        ));
        $this->add(array(     
            'type' => 'Zend\Form\Element\Select',       
            'name' => 'pages',
            'attributes' =>  array(
               'id' => 'pages'              
            ),
            'options' => array(
                'options' => array(
                    'aboutus' => 'About Us',
                ),
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'class' => 'btn btn-lg btn-primary btn-block',
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
        
    }
}