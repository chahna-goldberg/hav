<?php
namespace BrainpopUser\Form;

use Zend\Form\Factory as FormFactory;
use Zend\Form\Form;

class AccountForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->init();
    }

    public function init()
    {
        $factory    = new FormFactory();
        $name       = $this->getName();
        if (null === $name) {
            $this->setName('account');
        }

        $this->add($factory->createElement(array(
            'name'  => 'name',
            'options' => array(
                'label' => 'Name:',
            ),
        )));

        $this->add($factory->createElement(array(
            'name'  => 'email',
            'type' => 'Zend\Form\Element\Email',
            'options' => array(
                'label' => 'E-mail:',
            ),
        )));

        $this->add($factory->createElement(array(
            'name'  => 'max_users',
            'type' => 'Zend\Form\Element\Number',
            'options' => array(
                'label' => 'Max Users:',
            ),
        )));

        $this->add($factory->createElement(array(
            'name'  => 'max_groups',
            'type' => 'Zend\Form\Element\Number',
            'options' => array(
                'label' => 'Max Groups:',
            ),
        )));

        $this->add($factory->createElement(array(
            'name'  => 'status',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'options' => array(
                    'op1' => array (
                        'value' => 'active',
                        'label' => 'Active',
                        'selected' => true,
                        'disabled' => false
                    ),
                    'op2' => array (
                        'value' => 'suspended',
                        'label' => 'Suspended',
                    ),
                ),
            ),
            'options' => array(
                'label' => 'Status:',
            ),
        )));

        $this->add($factory->createElement(array(
            'name' => 'add',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Add',
            ),
        )));

        $this->add($factory->createElement(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        )));

        $this->add($factory->createElement(array(
            'name' => 'update',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Update',
            ),
        )));
    }
}
