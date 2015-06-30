<?php
namespace BrainpopUser\InputFilter;

use Zend\InputFilter\InputFilter;
use BrainpopUser\Mapper\AccountMapper;
use Zend\Db\Adapter\Adapter;

class AccountInputFilter extends InputFilter
{

    public function __construct(AccountMapper $mapper, Adapter $adapter)
    {

        $this->add(array(
            'name'       => 'name',
            'required'   => true,
            'filters'    => array(
                array(
                    'name'    => 'StripTags',
                ),
            ),
            'validators' => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min'      => 4,
                        'max'      => 14,
                        'messages'   => array(
                            'stringLengthTooShort'  => "Name is less than 4 characters.",
                            'stringLengthTooLong'   => "Name is more than 14 characters."
                        ),
                    ),
                    'break_chain_on_failure' => true,
                ),
                array(
                    'name'              => 'Callback',
                    'options'           => array(
                        'callback'      => array($mapper, 'isNameAvilable'),
                        'messages'      => array(
                            'callbackValue'  => "Account name already exists.",
                        ),
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'       => 'email',
            'required'   => true,
            'filters'    => array(
                array(
                    'name'    => 'StripTags',
                ),
            ),
            'validators' => array(
                array(
                    'name'    => 'EmailAddress',
                    'options' => array(
                        'domain' => false,
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'       => 'max_users',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'Digits',
                    'options' => array(
                        'messages' => array(
                            'notDigits' => "Max users allowed accept only positive integers",
                        ),
                    ),
                    'break_chain_on_failure' => true,
                ),
                array(
                    'name' => 'GreaterThan',
                    'options' => array(
                        'min'       => 0,
                        'messages'  => array(
                            'notGreaterThan' => "Max users allowed accept only positive integers",
                        ),
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'       => 'max_groups',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'Digits',
                    'options' => array(
                        'messages' => array(
                            'notDigits' => "Max groups allowed accept only positive integers",
                        ),
                    ),
                    'break_chain_on_failure' => true,
                ),
                array(
                    'name' => 'GreaterThan',
                    'options' => array(
                        'min'       => 0,
                        'messages'  => array(
                            'notGreaterThan' => "Max groups allowed accept only positive integers",
                        ),
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'       => 'status',
            'required'   => true,
        ));

        $this->add(array(
            'name'       => 'id',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'Digits',
                    'options' => array(
                        'messages' => array(
                            'notDigits' => "User ID is absent. Update failed",
                        ),
                    ),
                ),
                array(
                    'name'      => 'Db\RecordExists',
                    'options'   => array(
                        'table'     => 'accounts',
                        'field'     => 'account_id',
                        'adapter'   => $adapter,
                        'messages'   => array(
                            'recordFound'   => 'This account does not exist',
                        ),
                    ),
                ),
            ),
        ));

    } // End of __construct

    /**
     * Set the validation group without the ID. Needed when validating unsaved entity
     */
    public function setValidationWithoutId()
    {
        $this->setValidationGroup(array('name', 'email', 'max_users', 'max_groups', 'status'));
    }

}
