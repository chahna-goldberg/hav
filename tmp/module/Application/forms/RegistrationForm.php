<?php

/**
 * Created by PhpStorm.
 * User: alain
 * Date: 6/16/15
 * Time: 1:06 PM
 */
class RegistrationForm extends Zend_Form
{
    public function init()
    {
        $firstname = $this->createElement('text', 'firstname');
        $firstname->setLabel('First Name:')
            ->setRequired(false);

        $lastname = $this->createElement('text', 'lastname');
        $lastname->setLabel('Last Name:')
            ->setRequired(false);

        $email = $this->createElement('text', 'email');
        $email->setLabel('Email: *')
            ->setRequired(false);

        $username = $this->createElement('text', 'username');
        $username->setLabel('Username: *')
            ->setRequired(true);

        $password = $this->createElement('password', 'password');
        $password->setLabel('Password: *')
            ->setRequired(true);

        $confirmPassword = $this->createElement('password', 'confirmPassword');
        $confirmPassword->setLabel('Confirm Password: *')
            ->setRequired(true);

        $register = $this->createElement('submit', 'register');
        $register->setLabel('Sign up')
            ->setIgnore(true);

        $this->addElements(array(
            $firstname,
            $lastname,
            $email,
            $username,
            $password,
            $confirmPassword,
            $register
        ));

    }
}