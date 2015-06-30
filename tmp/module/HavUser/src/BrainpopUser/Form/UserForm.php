<?php
namespace BrainpopUser\Form;

use Zend\Form\Form;

class UserForm extends Form
{

    public function __construct()
    {
        parent::__construct('add_user');

        $this->add(array(
            'name'  => 'username',
            'options' => array(
                'label' => 'Username:',
            ),
            'attributes' => array(
                'placeholder'   => 'User Name',
            ),
        ));

        $this->add(array(
            'name'  => 'password',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'type'  => 'password',
            ),
            'options' => array(
                'label' => 'Password:',
            ),
            'attributes' => array(
                'placeholder'   => 'Password',
            ),
        ));

        $this->add(array(
            'name'  => 'email',
            'type' => 'Zend\Form\Element\Email',
            'options' => array(
                'label' => 'E-mail:',
            ),
            'attributes' => array(
                'placeholder'   => 'E-mail',
            ),
        ));

        $this->add(array(
            'name'  => 'firstName',
            'options' => array(
                'label' => 'First Name:',
            ),
            'attributes' => array(
                'placeholder'   => 'First name',
            ),
        ));

        $this->add(array(
            'name'  => 'lastName',
            'options' => array(
                'label' => 'Last Name:',
            ),
            'attributes' => array(
                'placeholder'   => 'Last name',
            ),
        ));

        $this->add(array(
            'name'  => 'role',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'options' => array(
                    'op1' => array (
                        'value' => 'bp admin',
                        'label' => 'BrainPOP Admin',
                    ),
                    'op2' => array (
                        'value' => 'bp author',
                        'label' => 'BrainPOP Author',
                    ),
                    'op3' => array (
                        'value' => 'account admin',
                        'label' => 'Account Admin',
                    ),
                    'op4' => array (
                        'value' => 'teacher',
                        'label' => 'Teacher',
                    ),
                    'op5' => array (
                        'value' => 'student',
                        'label' => 'Student',
                    ),
                    'op6' => array (
                        'value' => 'free student',
                        'label' => 'Free Student',
                    ),
                    'op7' => array (
                        'value' => 'qa student',
                        'label' => 'QA Student',
                    ),
                ),
            ),
            'options' => array(
                'label' => 'Role:',
            ),
        ));

        $this->add(array(
            'name' => 'add',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Add',
            ),
        ));

        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'accountId',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        $this->add(array(
            'name' => 'update',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Update',
            ),
        ));
    }
}
