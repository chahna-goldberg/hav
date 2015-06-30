<?php
namespace BrainpopUser\InputFilter;

use Zend\InputFilter\InputFilter;
use Zend\Db\Adapter\Adapter;

use BrainpopUser\Repository\AccountRepository;

class AddGroupFilter extends InputFilter
{
    
    public function __construct(Adapter $adapter, AccountRepository $accountRepo, $accountId)
    {

        $this->add(array(
            'name'       => 'account_id',
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
                        'table'     => 'accounts',
                        'field'     => 'account_id',
                        'adapter'   => $adapter,
                        'messages'   => array(
                            'noRecordFound'   => 'This account does not exist',
                        ),
                    ),
                ),
                array(
                    'name'              => 'Callback',
                    'options'           => array(
                        'callback'      => array($accountRepo, 'canAddGroup'),
                        'messages'      => array(
                            'callbackValue'  => "The account reached its max group limit.",
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
                        'exclude'   => "account_id={$accountId}",
                        'messages'   => array(
                            'recordFound'   => 'Name already exists.',
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
