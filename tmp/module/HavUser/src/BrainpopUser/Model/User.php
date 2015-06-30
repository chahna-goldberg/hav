<?php
namespace BrainpopUser\Model;

use BrainpopUser\Model\UserMapper;

class User
{
    protected $mapper;
    protected $form_data;

    /**
     * Make the mapper object avilable as local prtected variable
     * @param UserMapper $mapper 
     */
    public function __construct(UserMapper $mapper)
    {
        $this->mapper       = $mapper;
        $this->form_data    = array();
    }

    /**
     * Accept form values that where binded by the $form->bind() method
     * @return array
     */
    public function exchangeArray(array $array)
    {
        $this->form_data = $array;
    }

    /**
     * Return an array representation of the object (needed to satisfy the class implements)
     * @return array
     */
    public function getArrayCopy()
    {
        return $this->form_data;
    }
    
    /**
     * list users that are allowed to shared with
     * @return array 
     */
    public function listShareableUsers()
    {
        $user_id    = (int) $this->auth->getStorage()->read()->user_id;
        $account_id = (int) $this->auth->getStorage()->read()->account_id;
        return $this->mapper->listShareableUsers($account_id, $user_id);
    }

    /**
     * The auth object is injected by the service manager
     * @param AuthenticationService $auth 
     */
    public function setAuth ($auth)
    {
        $this->auth = $auth;
    }
}