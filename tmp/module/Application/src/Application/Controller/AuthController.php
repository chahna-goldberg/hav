<?php

class AuthController extends Zend_Controller_Action
{
    public function homeAction()
    {
        $storage = new Zend_Auth_Storage_Session();
        $data = $storage->read();
        if (!$data) {
            $this->_redirect('auth/login');
        }
        $this->view->username = $data->username;
    }

    public function loginAction()
    {
        $users = new Users();
        $form = new LoginForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                $data = $form->getValues();
                $auth = Zend_Auth::getInstance();
                $authAdapter = new Zend_Auth_Adapter_DbTable($users->getAdapter(), 'users');
                $authAdapter->setIdentityColumn('username')
                    ->setCredentialColumn('password');
                $authAdapter->setIdentity($data['username'])
                    ->setCredential($data['password']);
                $result = $auth->authenticate($authAdapter);
                if ($result->isValid()) {
                    $storage = new Zend_Auth_Storage_Session();
                    $storage->write($authAdapter->getResultRowObject());
                    $this->_redirect('auth/home');
                } else {
                    $this->view->errorMessage = "Invalid username or password . Please try again .";
                }
            }
        }
    }

    public function signupAction()
    {
        $users = new Users();
        $form = new RegistrationForm();
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                $data = $form->getValues();
                if ($data['password'] != $data['confirmPassword']) {
                    $this->view->errorMessage = "Password and confirm password don't match.";
                    return;
                }
                if ($users->checkUnique($data['username'])) {
                    $this->view->errorMessage = "Name already taken. Please choose another one.";
                    return;
                }
                unset($data['confirmPassword']);
                $users->insert($data);
                $this->_redirect('auth/login');
            }
        }
    }

    public function logoutAction()
    {
        $storage = new Zend_Auth_Storage_Session();
        $storage->clear();
        $this->_redirect('auth/login');
    }
}