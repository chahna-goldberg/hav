<?php
namespace BrainpopUser\Form;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

class LoginFilter extends InputFilter
{
    public function __construct()
    {
        $factory = new InputFactory();

        $this->add($factory->createInput(array(
            'name'       => 'account_id',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'Digits',
                ),
                array(
                    'name' => 'GreaterThan',
                    'options' => array( 'min' => 1 ),
                ),
            ),
        )));

        $this->add($factory->createInput(array(
            'name'       => 'username',
            'required'   => true,
            'filters'    => array(
                array(
                    'name'    => 'StripTags',
                ),
            ),
        )));

        $this->add($factory->createInput(array(
            'name'       => 'password',
            'required'   => true,
        )));

    }
}
