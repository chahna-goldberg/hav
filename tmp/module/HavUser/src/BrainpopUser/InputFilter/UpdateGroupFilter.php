<?php
namespace BrainpopUser\InputFilter;

use Zend\InputFilter\InputFilter;
use Zend\Db\Adapter\Adapter;

class UpdateGroupFilter extends InputFilter
{
    
    public function __construct(Adapter $adapter, $groupId, $accountId)
    {

        $this->add(array(
            'name'       => 'id',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'Digits',
                    'options' => array(
                        'messages' => array(
                            'notDigits' => "Group ID is absent. Update failed",
                        ),
                    ),
                ),
                array(
                    'name'      => 'Db\RecordExists',
                    'options'   => array(
                        'table'     => 'groups',
                        'field'     => 'id',
                        'adapter'   => $adapter,
                        'messages'   => array(
                            'recordFound'   => 'This group does not exist',
                        ),
                    ),
                ),
            ),
        ));

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
                            'stringLengthTooShort'  => "Group name is less than 4 characters.",
                            'stringLengthTooLong'   => "Group name is more than 14 characters."
                        ),
                    ),
                ),
                array(
                    'name'      => 'Db\NoRecordExists',
                    'options'   => array(
                        'table'     => 'groups',
                        'field'     => 'name',
                        'adapter'   => $adapter,
                        'messages'   => array(
                            'recordFound'   => 'Name already exists.',
                        ),
                        'exclude' => array(
                            'field' => 'id',
                            'value' => $groupId,
                        ),
                        'include' => array(
                            'field' => 'account_id',
                            'value' => $accountId,
                        ),
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name'       => 'invites_bank',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'Digits',
                    'options' => array(
                        'messages' => array(
                            'notDigits' => "Invites bank execpt digits of 0 and more",
                        ),
                    ),
                ),
                array(
                    'name' => 'GreaterThan',
                    'options' => array(
                        'min'       => 0,
                        'inclusive' => true,
                        'messages'  => array(
                            'notGreaterThanInclusive'   => 'This invites are less then 0',
                        ),
                    ),
                ),
            ),
        ));

    } // End of __construct
}
