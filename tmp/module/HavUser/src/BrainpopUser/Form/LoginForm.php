<?php
namespace BrainpopUser\Form;

use Zend\Form\Factory as FormFactory;
use Zend\Form\Form;

class LoginForm extends Form
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
            $this->setName('login');
        }

        $this->add($factory->createElement(array(
            'name'  => 'account_id',
            'type' => 'Zend\Form\Element\Number',
            'options' => array(
                'label' => 'Account ID:',
            ),
            'attributes' => array(
                'autofocus' => 'autofocus',
            ),
        )));

        $this->add($factory->createElement(array(
            'name'  => 'username',
            'options' => array(
                'label' => 'User:',
            ),
        )));

        $this->add($factory->createElement(array(
            'name'  => 'password',
            'type' => 'Zend\Form\Element\Password',
            'options' => array(
                'label' => 'Password:',
            ),
        )));

        $this->add($factory->createElement(array(
            'name' => 'Send',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Login',
            ),
        )));
    }
}
