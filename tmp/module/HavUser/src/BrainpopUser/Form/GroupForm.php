<?php
namespace BrainpopUser\Form;

use Zend\Form\Factory as FormFactory;
use Zend\Form\Form;

class GroupForm extends Form
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
            $this->setName('group');
        }

        $this->add($factory->createElement(array(
            'name' => 'account_id',
            'type' => 'Zend\Form\Element\Hidden',
        )));

        $this->add($factory->createElement(array(
            'name'  => 'name',
            'options' => array(
                'label' => 'Name:',
            ),
            'attributes' => array(
                'placeholder'   => 'Group name',
            ),
        )));

        $this->add($factory->createElement(array(
            'name'  => 'invites_bank',
            'type' => 'Zend\Form\Element\Number',
            'options' => array(
                'label' => 'Invites Bank:',
            ),
            'attributes' => array(
                'placeholder'   => 'Invites bank',
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
