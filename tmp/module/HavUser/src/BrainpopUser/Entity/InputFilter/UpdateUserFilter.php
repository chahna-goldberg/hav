<?php
namespace BrainpopUser\Entity\InputFilter;

use Zend\InputFilter\InputFilter;
use Zend\Db\Adapter\Adapter;

class UpdateUserFilter extends InputFilter
{
    /**
     * Update User InputFilter
     * @param int $accountId
     * @param Adapter $adapter
     * @param int $id User ID
     */
    public function __construct($accountId, Adapter $adapter, $id)
    {

        $this->add(array(
            'name'       => 'id',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'Digits',
                    'options' => array(
                        'messages' => array(
                            'notDigits' => "Account ID is absent. Update failed",
                        ),
                    ),
                ),
                array(
                    'name'      => 'Db\RecordExists',
                    'options'   => array(
                        'table'     => 'users',
                        'field'     => 'user_id',
                        'adapter'   => $adapter,
                        'messages'   => array(
                            'recordFound'   => 'This user does not exist',
                        ),
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'       => 'username',
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
                            'stringLengthTooShort'  => "Username is less than 4 characters.",
                            'stringLengthTooLong'   => "Username is more than 14 characters."
                        ),
                    ),
                ),
                array(
                    'name'      => 'Db\NoRecordExists',
                    'options'   => array(
                        'table'     => 'users',
                        'field'     => 'username',
                        'exclude'   => "account_id = {$accountId} AND user_id != {$id}",
                        'adapter'   => $adapter,
                        'messages'   => array(
                            'recordFound'   => 'Username already exists.',
                        ),
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'          => 'password',
            'required'      => true,
            'allow_empty'   => true,
            'validators'    => array(
                array(
                    'name'    => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min'      => 6,
                        'max'      => 16,
                        'messages'   => array(
                            'stringLengthTooShort'  => "Password is less than 6 characters.",
                            'stringLengthTooLong'   => "Password is more than 16 characters."
                        ),
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'          => 'email',
            'required'      => true,
            'allow_empty'   => true,
            'validators'    => array(
                array(
                    'name'    => 'EmailAddress',
                    'options' => array(
                        'domain' => false,
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'       => 'firstName',
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
                        'min'      => 2,
                        'max'      => 16,
                        'messages'   => array(
                            'stringLengthTooShort'  => "First name is less than 2 characters.",
                            'stringLengthTooLong'   => "First name is more than 16 characters."
                        ),
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'       => 'lastName',
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
                        'min'      => 2,
                        'max'      => 16,
                        'messages'   => array(
                            'stringLengthTooShort'  => "Last name is less than 2 characters.",
                            'stringLengthTooLong'   => "Last name is more than 16 characters."
                        ),
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'       => 'role',
            'required'   => true,
        ));

    } // End of __construct
}
