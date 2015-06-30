<?php
namespace BrainpopUser\Form;

use Zend\Form\Factory as FormFactory;
use Zend\Form\Form;

class UpdatePasswordForm extends Form
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
            $this->setName('update_password');
        }

        $this->add($factory->createElement(array(
            'name'  => 'password',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'type'  => 'password',
            ),
            'options' => array(
                'label' => 'Password:',
            ),
        )));

        $this->add($factory->createElement(array(
            'name'  => 'password2',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'type'  => 'password',
            ),
            'options' => array(
                'label' => 'Retype:',
            ),
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
