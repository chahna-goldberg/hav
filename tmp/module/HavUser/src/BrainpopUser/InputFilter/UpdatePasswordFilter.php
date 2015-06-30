<?php
namespace BrainpopUser\InputFilter;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

class UpdatePasswordFilter extends InputFilter
{
    
    public function __construct()
    {

        $factory = new InputFactory();

        $this->add($factory->createInput(array(
            'name'       => 'password',
            'required'   => true,
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min'      => 6,
                        'max'      => 16,
                        'messages'   => array(
                            'stringLengthTooShort'  => "Password is less than 6 characters.",
                            'stringLengthTooLong'   => "Password is more than 16 characters long."
                        ),
                    ),
                ),
            ),
        )));

        $this->add($factory->createInput(array(
            'name'       => 'password2',
            'required'   => true,
            'validators' => array(
                array(
                    'name'      => 'identical',
                    'options'   => array(
                        'strict'    => false,
                        'token'     => 'password',
                        'messages'  => array(
                            'notSame'   => "The two given passwords do not match",
                        ),
                    ),
                ),
            ),
        )));


    } // End of __construct
}
