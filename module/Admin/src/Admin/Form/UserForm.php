<?php
// module/Admin/src/Admin/Form/AdminForm.php:
namespace Admin\Form;

use Zend\Form\Form;

class UserForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('admin');
        $this->setAttribute('method', 'post');
        
        //Add form element
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        
        $this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'text',
                'class' => 'form-control',
                'placeholder' => 'Name',
                'id' => 'text1',
            ),
        ));

        $this->add(array(
            'name' => 'address',
            'attributes' => array(
                'type'  => 'textarea',
                'class' => 'form-control',
                'placeholder' => 'Address',
                'id' => 'text4',
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Radio',
            'name' => 'status',
            'class' => 'uniform',
            'options' => array(
                'label' => 'Status',
                'value_options' => array(
                    '1' => 'Active',
                    '0' => 'Deactive',
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